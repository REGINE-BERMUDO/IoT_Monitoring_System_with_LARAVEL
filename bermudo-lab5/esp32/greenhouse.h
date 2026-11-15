#ifndef GREENHOUSE_H
#define GREENHOUSE_H

#include <Arduino.h>

// ===== Settings (no Wi-Fi name, password or token stored in the code) =====
#define DEVICE_ID          "ESP32-GREENHOUSE-01"
#define SETUP_AP_NAME      "Greenhouse-Setup"    // Wi-Fi the ESP32 creates for setup
#define SETUP_AP_PASSWORD  "greenhouse123"       // at least 8 characters

// 0 = real DHT11 sensor connected, 1 = no sensor (send random test values)
#define USE_SIMULATED_DATA 0

#define DHTPIN             4
#define DHTTYPE            DHT11
#define RESET_BUTTON_PIN   0                     // BOOT button on most ESP32 boards
#define RESET_HOLD_MS      3000UL                // hold BOOT this long to forget Wi-Fi
#define SEND_INTERVAL_MS   30000UL               // send a reading every 30 seconds

// ===== Functions (written in greenhouse.cpp) =====
void initHardware();
void connectWiFi();
void syncTime();
void sendReading();
void checkResetButton();

#endif