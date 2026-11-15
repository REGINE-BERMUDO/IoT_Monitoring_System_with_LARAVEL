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