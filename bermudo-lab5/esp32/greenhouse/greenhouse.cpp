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