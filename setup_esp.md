# ESP32 IoT Setup for AIoT-Based Firing Scoring System

This document explains how to set up the **ESP32 IoT device** used in the **AIoT-Enabled Electronic Target Scoring System**.
The ESP32 collects environmental and range data and sends it to a **Laravel backend API** for storage and analysis.

---

## 1. System Overview

The ESP32 works as an **IoT data collector**.  
It does **not** score targets directly. Instead, it provides **contextual data** that supports:

- Training condition analysis  
- Data-driven feedback  
- System validation  

### Data collected by ESP32
- Firing distance  
- Temperature  
- Humidity  
- Light intensity  

Each data packet is linked to a **shooting session** in the database.

---

## 2. Supported ESP32 Boards

- **ESP32 DevKit**
- **ESP32-CAM (AI Thinker)**
- **ESP32-S3 camera boards**

This README includes ready-to-upload codes for:
- ESP32 DevKit (recommended for stable sensor testing)
- ESP32-CAM (if you want camera + sensors on one board)

---

## 3. Required Hardware Components

### Core
- ESP32 board
- USB cable

### Sensors (I2C)
| Sensor | Purpose |
|------|--------|
| BME280 / SHT31 | Temperature & humidity |
| BH1750 | Light intensity (lux) |
| VL53L0X | Distance measurement |

All sensors share the same I2C bus.

---

## 4. Wiring Configuration

### ESP32 DevKit (Default)
| Signal | GPIO |
|------|------|
| SDA | GPIO 21 |
| SCL | GPIO 22 |
| VCC | 3.3V |
| GND | GND |

### ESP32-CAM (Common Setup)
| Signal | GPIO |
|------|------|
| SDA | GPIO 14 |
| SCL | GPIO 15 |
| VCC | 3.3V |
| GND | GND |

> Tip: If sensors are not detected on ESP32-CAM, your pinout may differ. Double-check your board.

---

## 5. Required Arduino Libraries

Install using **Arduino IDE → Library Manager**:

- ArduinoJson (Benoit Blanchon)
- Adafruit BME280 Library
- Adafruit Unified Sensor
- BH1750 (Christopher Laws)
- Adafruit VL53L0X

---

## 6. Network Configuration (VERY IMPORTANT)

### Why you should NOT use 127.0.0.1
`127.0.0.1` means **the ESP32 itself**, not your Mac.

### Laravel must be reachable on your LAN
Run Laravel like this so other devices can access it:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### Find your Mac LAN IP
On macOS:

```bash
ipconfig getifaddr en0
```

Then update the ESP32 code like:

```cpp
const char* SERVER_URL = "http://192.168.1.25:8000/api/iot-readings";
```

---

## 7. Session ID Requirement

Each IoT reading must be linked to a valid shooting session:

```cpp
const int SESSION_ID = 3;
```

Make sure `shooting_sessions.id = 3` exists.

---

## 8. Data Format Sent to Server

The ESP32 sends JSON like this:

```json
{
  "device_id": "ESP32-DEVKIT-01",
  "session_id": 3,
  "distance_m": 25.0,
  "temperature_c": 30.2,
  "humidity_percent": 68.5,
  "light_lux": 420.0
}
```

---

# 9. ESP32 Code (ESP32 DevKit)

✅ Use this first if you want the easiest sensor + API testing.

### Update these before upload
- `WIFI_SSID`
- `WIFI_PASS`
- `SERVER_URL` (use your Mac LAN IP)
- `SESSION_ID` (must exist in DB)

```cpp
#include <WiFi.h>
#include <HTTPClient.h>
#include <Wire.h>

#include <ArduinoJson.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BME280.h>
#include <BH1750.h>
#include <Adafruit_VL53L0X.h>

// ------------------- WIFI + SERVER -------------------
const char* WIFI_SSID = "YOUR_WIFI_NAME";
const char* WIFI_PASS = "YOUR_WIFI_PASSWORD";

// IMPORTANT: Use your Mac LAN IP (NOT 127.0.0.1)
const char* SERVER_URL = "http://192.168.1.25:8000/api/iot-readings";

const char* DEVICE_ID = "ESP32-DEVKIT-01";
const int SESSION_ID = 3; // must exist in shooting_sessions

// ------------------- SENSORS -------------------
Adafruit_BME280 bme;
BH1750 lightMeter;
Adafruit_VL53L0X lox = Adafruit_VL53L0X();

bool bme_ok = false;
bool bh_ok = false;
bool vl_ok = false;

void connectWifi() {
  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASS);

  Serial.print("Connecting to WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(400);
    Serial.print(".");
  }
  Serial.println("\nWiFi connected!");
  Serial.print("ESP32 IP: ");
  Serial.println(WiFi.localIP());
}

void setupSensors() {
  // ESP32 DevKit default I2C pins
  Wire.begin(21, 22); // SDA=21, SCL=22

  // BME280 (common address: 0x76 or 0x77)
  bme_ok = bme.begin(0x76);
  if (!bme_ok) bme_ok = bme.begin(0x77);

  // BH1750
  bh_ok = lightMeter.begin(BH1750::CONTINUOUS_HIGH_RES_MODE);

  // VL53L0X
  vl_ok = lox.begin();

  Serial.println("Sensor status:");
  Serial.print("BME280: "); Serial.println(bme_ok ? "OK" : "FAILED");
  Serial.print("BH1750: "); Serial.println(bh_ok ? "OK" : "FAILED");
  Serial.print("VL53L0X: "); Serial.println(vl_ok ? "OK" : "FAILED");
}

float readTemperatureC() { return bme_ok ? bme.readTemperature() : NAN; }
float readHumidity()     { return bme_ok ? bme.readHumidity() : NAN; }
float readLightLux()     { return bh_ok ? lightMeter.readLightLevel() : NAN; }

float readDistanceMeters() {
  if (!vl_ok) return NAN;

  VL53L0X_RangingMeasurementData_t measure;
  lox.rangingTest(&measure, false);

  if (measure.RangeStatus != 4) {
    return measure.RangeMilliMeter / 1000.0; // mm -> meters
  }
  return NAN;
}

bool postReading(float distance_m, float temp_c, float hum, float lux) {
  if (WiFi.status() != WL_CONNECTED) return false;

  HTTPClient http;
  http.begin(SERVER_URL);
  http.addHeader("Content-Type", "application/json");

  StaticJsonDocument<512> doc;
  doc["device_id"] = DEVICE_ID;
  doc["session_id"] = SESSION_ID;

  if (!isnan(distance_m)) doc["distance_m"] = distance_m;
  if (!isnan(temp_c))     doc["temperature_c"] = temp_c;
  if (!isnan(hum))        doc["humidity_percent"] = hum;
  if (!isnan(lux))        doc["light_lux"] = lux;

  String payload;
  serializeJson(doc, payload);

  Serial.println("\nPOST payload:");
  Serial.println(payload);

  int code = http.POST(payload);
  String resp = http.getString();

  Serial.print("HTTP code: ");
  Serial.println(code);
  Serial.print("Response: ");
  Serial.println(resp);

  http.end();
  return (code >= 200 && code < 300);
}

void setup() {
  Serial.begin(115200);
  delay(500);

  connectWifi();
  setupSensors();
}

void loop() {
  float dist_m = readDistanceMeters();
  float temp_c = readTemperatureC();
  float hum    = readHumidity();
  float lux    = readLightLux();

  postReading(dist_m, temp_c, hum, lux);

  delay(5000); // send every 5 seconds
}
```

---

# 10. ESP32 Code (ESP32-CAM AI Thinker)

✅ Use this if you are on ESP32-CAM and you want sensors + telemetry.

### Common I2C pins on ESP32-CAM
- SDA = GPIO 14
- SCL = GPIO 15

```cpp
#include <WiFi.h>
#include <HTTPClient.h>
#include <Wire.h>

#include <ArduinoJson.h>
#include <Adafruit_Sensor.h>
#include <Adafruit_BME280.h>
#include <BH1750.h>
#include <Adafruit_VL53L0X.h>

// ------------------- WIFI + SERVER -------------------
const char* WIFI_SSID = "YOUR_WIFI_NAME";
const char* WIFI_PASS = "YOUR_WIFI_PASSWORD";

// IMPORTANT: Use your Mac LAN IP (NOT 127.0.0.1)
const char* SERVER_URL = "http://192.168.1.25:8000/api/iot-readings";

const char* DEVICE_ID = "ESP32-CAM-01";
const int SESSION_ID = 3; // must exist in shooting_sessions

// ------------------- I2C PINS (ESP32-CAM) -------------------
const int I2C_SDA = 14;
const int I2C_SCL = 15;

// ------------------- SENSORS -------------------
Adafruit_BME280 bme;
BH1750 lightMeter;
Adafruit_VL53L0X lox = Adafruit_VL53L0X();

bool bme_ok = false;
bool bh_ok = false;
bool vl_ok = false;

void connectWifi() {
  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASS);

  Serial.print("Connecting to WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(400);
    Serial.print(".");
  }
  Serial.println("\nWiFi connected!");
  Serial.print("ESP32 IP: ");
  Serial.println(WiFi.localIP());
}

void setupSensors() {
  Wire.begin(I2C_SDA, I2C_SCL);

  bme_ok = bme.begin(0x76);
  if (!bme_ok) bme_ok = bme.begin(0x77);

  bh_ok = lightMeter.begin(BH1750::CONTINUOUS_HIGH_RES_MODE);

  vl_ok = lox.begin();

  Serial.println("Sensor status:");
  Serial.print("BME280: "); Serial.println(bme_ok ? "OK" : "FAILED");
  Serial.print("BH1750: "); Serial.println(bh_ok ? "OK" : "FAILED");
  Serial.print("VL53L0X: "); Serial.println(vl_ok ? "OK" : "FAILED");
}

float readTemperatureC() { return bme_ok ? bme.readTemperature() : NAN; }
float readHumidity()     { return bme_ok ? bme.readHumidity() : NAN; }
float readLightLux()     { return bh_ok ? lightMeter.readLightLevel() : NAN; }

float readDistanceMeters() {
  if (!vl_ok) return NAN;

  VL53L0X_RangingMeasurementData_t measure;
  lox.rangingTest(&measure, false);

  if (measure.RangeStatus != 4) {
    return measure.RangeMilliMeter / 1000.0;
  }
  return NAN;
}

bool postReading(float distance_m, float temp_c, float hum, float lux) {
  if (WiFi.status() != WL_CONNECTED) return false;

  HTTPClient http;
  http.begin(SERVER_URL);
  http.addHeader("Content-Type", "application/json");

  StaticJsonDocument<512> doc;
  doc["device_id"] = DEVICE_ID;
  doc["session_id"] = SESSION_ID;

  if (!isnan(distance_m)) doc["distance_m"] = distance_m;
  if (!isnan(temp_c))     doc["temperature_c"] = temp_c;
  if (!isnan(hum))        doc["humidity_percent"] = hum;
  if (!isnan(lux))        doc["light_lux"] = lux;

  String payload;
  serializeJson(doc, payload);

  Serial.println("\nPOST payload:");
  Serial.println(payload);

  int code = http.POST(payload);
  String resp = http.getString();

  Serial.print("HTTP code: ");
  Serial.println(code);
  Serial.print("Response: ");
  Serial.println(resp);

  http.end();
  return (code >= 200 && code < 300);
}

void setup() {
  Serial.begin(115200);
  delay(500);

  connectWifi();
  setupSensors();
}

void loop() {
  float dist_m = readDistanceMeters();
  float temp_c = readTemperatureC();
  float hum    = readHumidity();
  float lux    = readLightLux();

  postReading(dist_m, temp_c, hum, lux);

  delay(5000);
}
```

---

## 11. Upload Steps (Arduino IDE)

1. Open **Arduino IDE**
2. Install libraries (Section 5)
3. Select **Tools → Board**
   - ESP32 DevKit: “ESP32 Dev Module”
   - ESP32-CAM: “AI Thinker ESP32-CAM” (if available)
4. Select correct **Port**
5. Paste code and click **Upload**
6. Open **Serial Monitor**
   - Baud rate: **115200**

---

## 12. Quick Troubleshooting

### A) Sensor shows FAILED
- Check wiring (SDA/SCL swapped is common)
- Confirm sensor I2C address (BME280 is usually 0x76 or 0x77)
- Use a shorter jumper wires

### B) ESP32 cannot reach server
- Make sure Laravel is running with `--host=0.0.0.0`
- Make sure your Mac IP is correct
- Ensure ESP32 and Mac are on the same Wi-Fi network
- If macOS firewall prompts, click **Allow**

### C) API says “session id invalid”
- The session must exist in `shooting_sessions`
- Create a test session first in Laravel/Tinker

---

## 13. Verification Checklist

- ESP32 connects to Wi-Fi
- Sensor values appear in Serial Monitor
- API response returns `{ "ok": true }`
- Data appears in `iot_readings` table

---

This setup is designed to be **simple, reliable, and easy to explain** during capstone/thesis documentation.
