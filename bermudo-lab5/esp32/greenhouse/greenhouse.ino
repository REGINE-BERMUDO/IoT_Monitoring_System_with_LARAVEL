#include <WiFi.h>
#include <HTTPClient.h>
#include <time.h>

// ===== CHANGE THESE =====
const char* WIFI_SSID     = "YOUR_WIFI_NAME";
const char* WIFI_PASSWORD = "YOUR_WIFI_PASSWORD";
const char* SERVER_URL    = "http://192.168.1.10:8000/api/iot/readings";
const char* DEVICE_TOKEN  = "replace-with-the-same-token-as-IOT_DEVICE_TOKEN";
const char* DEVICE_ID     = "ESP32-GREENHOUSE-01";

// 0 = real DHT11 sensor connected, 1 = no sensor (send random test values)
#define USE_SIMULATED_DATA 0

#if !USE_SIMULATED_DATA
  #include <DHT.h>
  #define DHTPIN 4
  #define DHTTYPE DHT11
  DHT dht(DHTPIN, DHTTYPE);
#endif

unsigned long previousMillis = 0;
const unsigned long interval = 30000;   // send every 30 seconds

void connectWiFi() {
  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  Serial.print("Connecting to Wi-Fi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println();
  Serial.print("ESP32 IP: ");
  Serial.println(WiFi.localIP());
}

void sendReading() {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("Wi-Fi disconnected.");
    return;
  }

#if USE_SIMULATED_DATA
  float temperature = random(250, 331) / 10.0;   // 25.0 - 33.0
  float humidity    = random(550, 851) / 10.0;   // 55.0 - 85.0
#else
  float temperature = dht.readTemperature();
  float humidity    = dht.readHumidity();
  if (isnan(temperature) || isnan(humidity)) {
    Serial.println("Failed to read DHT sensor.");
    return;
  }
#endif

  struct tm timeinfo;
  char timestamp[25];
  if (!getLocalTime(&timeinfo, 2000)) {
    Serial.println("Time not synchronized; reading skipped.");
    return;
  }
  strftime(timestamp, sizeof(timestamp), "%Y-%m-%dT%H:%M:%SZ", &timeinfo);

  String payload = "{";
  payload += "\"device_id\":\"";
  payload += DEVICE_ID;
  payload += "\",\"temperature\":";
  payload += String(temperature, 2);
  payload += ",\"humidity\":";
  payload += String(humidity, 2);
  payload += ",\"recorded_at\":\"";
  payload += timestamp;
  payload += "\"}";

  HTTPClient http;
  http.setTimeout(5000);
  http.begin(SERVER_URL);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("Accept", "application/json");

  String authorization = "Bearer ";
  authorization += DEVICE_TOKEN;
  http.addHeader("Authorization", authorization);

  int httpCode = http.POST(payload);

  Serial.print("Temperature: ");
  Serial.println(temperature);
  Serial.print("Humidity: ");
  Serial.println(humidity);
  Serial.print("HTTP status: ");
  Serial.println(httpCode);

  if (httpCode > 0) {
    Serial.println(http.getString());
  } else {
    Serial.println(http.errorToString(httpCode));
  }

  http.end();
}