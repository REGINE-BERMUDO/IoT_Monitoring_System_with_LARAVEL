#include "greenhouse.h"

unsigned long previousMillis = 0;

void setup() {
  Serial.begin(115200);

  initHardware();
  connectWiFi();
  syncTime();

  sendReading();
  previousMillis = millis();
}

void loop() {
  checkResetButton();

  if (millis() - previousMillis >= SEND_INTERVAL_MS) {
    previousMillis = millis();
    sendReading();
  }
}