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