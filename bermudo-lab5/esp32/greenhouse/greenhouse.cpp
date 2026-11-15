#include "greenhouse.h"

#include <WiFi.h>
#include <WiFiManager.h>      // "WiFiManager" library by tzapu
#include <HTTPClient.h>
#include <Preferences.h>
#include <time.h>

#if !USE_SIMULATED_DATA
  #include <DHT.h>
  static DHT dht(DHTPIN, DHTTYPE);
#endif

static Preferences preferences;
static String serverUrl   = "http://192.168.1.10:8000/api/iot/readings";
static String deviceToken = "";
static bool shouldSaveSettings = false;
static unsigned long resetPressedAt = 0;

static void loadSettings() {
  preferences.begin("greenhouse", true);   // true = read-only
  serverUrl   = preferences.getString("url", serverUrl);
  deviceToken = preferences.getString("token", deviceToken);
  preferences.end();
}

static void saveSettings() {
  preferences.begin("greenhouse", false);  // false = read and write
  preferences.putString("url", serverUrl);
  preferences.putString("token", deviceToken);
  preferences.end();
}

static void saveConfigCallback() {
  shouldSaveSettings = true;
}

void connectWiFi() {
  loadSettings();

  WiFiManagerParameter urlParam("url", "Laravel API URL", serverUrl.c_str(), 120);
  WiFiManagerParameter tokenParam("token", "Device token", deviceToken.c_str(), 100);

  WiFiManager wm;
  wm.setSaveConfigCallback(saveConfigCallback);
  wm.setConfigPortalTimeout(180);          // setup page stays open for 3 minutes
  wm.addParameter(&urlParam);
  wm.addParameter(&tokenParam);

  Serial.println("Connecting to saved Wi-Fi (setup portal opens if there is none)...");

  if (!wm.autoConnect(SETUP_AP_NAME, SETUP_AP_PASSWORD)) {
    Serial.println("Wi-Fi setup failed or timed out. Restarting...");
    delay(3000);
    ESP.restart();
  }

  if (shouldSaveSettings) {
    serverUrl   = String(urlParam.getValue());
    deviceToken = String(tokenParam.getValue());
    serverUrl.trim();
    deviceToken.trim();
    saveSettings();
    Serial.println("New settings saved.");
  }

  Serial.print("Connected to: ");
  Serial.println(WiFi.SSID());
  Serial.print("ESP32 IP: ");
  Serial.println(WiFi.localIP());
  Serial.print("Server URL: ");
  Serial.println(serverUrl);
}

void syncTime() {
  // Synchronize the clock to UTC using NTP
  configTime(0, 0, "pool.ntp.org", "time.google.com");
  struct tm timeinfo;
  if (!getLocalTime(&timeinfo, 15000)) {
    Serial.println("NTP synchronization failed.");
  }
}

void sendReading() {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("Wi-Fi disconnected.");
    return;
  }

  if (deviceToken.length() == 0) {
    Serial.println("No device token saved. Hold BOOT for 3 seconds to open setup again.");
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
  http.begin(serverUrl);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("Accept", "application/json");
  http.addHeader("Authorization", "Bearer " + deviceToken);

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

void initHardware() {
  pinMode(RESET_BUTTON_PIN, INPUT_PULLUP);

#if !USE_SIMULATED_DATA
  dht.begin();
#endif
}

void checkResetButton() {
  if (digitalRead(RESET_BUTTON_PIN) == LOW) {
    if (resetPressedAt == 0) {
      resetPressedAt = millis();
    } else if (millis() - resetPressedAt > RESET_HOLD_MS) {
      Serial.println("Forgetting saved Wi-Fi. Release the BOOT button now...");
      WiFiManager wm;
      wm.resetSettings();
      while (digitalRead(RESET_BUTTON_PIN) == LOW) {
        delay(50);
      }
      ESP.restart();
    }
  } else {
    resetPressedAt = 0;
  }
}