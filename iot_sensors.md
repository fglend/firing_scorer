# IoT Sensors Reference – AIoT Firing Scoring System

This document describes the **sensors used in the AIoT-Enabled Electronic Target Scoring System**, including their purpose, parameters, wiring, and recommended models compatible with **ESP32 boards**.

This README is suitable for:
- Capstone / thesis documentation
- System design appendix
- Hardware implementation guide

---

## 1. System Role of Sensors

The sensors are used to **capture environmental and range conditions** during live-fire training.  
They do **not score shots directly**, but provide contextual data that supports:

- Training condition assessment
- Shot detection reliability
- AI-based performance analysis
- Result interpretation and validation

---

## 2. Summary of Sensors Used

| Sensor | Type | Data Collected | Interface |
|------|----|----------------|-----------|
| VL53L0X | Time-of-Flight | Distance (meters) | I2C |
| BME280 / SHT31 | Environmental | Temperature, Humidity | I2C |
| BH1750 | Light Sensor | Light Intensity (lux) | I2C |

All sensors communicate via **I2C**, allowing them to share the same bus.

---

## 3. Distance Sensor – VL53L0X

### Purpose
Measures the **distance between firing line and target**, ensuring:
- correct training range
- consistency across sessions

### Parameters
- Measurement Unit: meters (m)
- Typical Range: 0.03 m – 2.0 m
- Resolution: ±3 mm

### Why VL53L0X?
- High accuracy
- Stable readings indoors
- Small form factor
- Widely supported on ESP32

### Recommended Module
- VL53L0X Time-of-Flight Distance Sensor (STMicroelectronics)

---

## 4. Temperature & Humidity Sensor – BME280 / SHT31

### Purpose
Captures **environmental conditions** that may affect:
- shooter comfort
- camera image quality
- bullet hole visibility

### Parameters
- Temperature: °C
- Humidity: %

### Comparison
| Feature | BME280 | SHT31 |
|------|------|------|
| Temp Accuracy | ±1.0°C | ±0.3°C |
| Humidity Accuracy | ±3% | ±2% |
| Cost | Lower | Slightly higher |

### Recommended Choice
- **BME280** for general use
- **SHT31** for higher accuracy requirements

---

## 5. Light Sensor – BH1750

### Purpose
Measures **ambient light intensity** to:
- assess lighting adequacy
- support image-based shot detection
- detect low-light conditions

### Parameters
- Measurement Unit: lux
- Range: 1 – 65,535 lux

### Interpretation
| Lux Range | Meaning |
|--------|--------|
| >300 lux | Good lighting |
| 150–300 lux | Low lighting |
| <150 lux | Poor lighting |

### Recommended Module
- BH1750 Digital Light Intensity Sensor

---

## 6. Wiring Overview (I2C)

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

⚠️ Always confirm your board pinout before wiring.

---

## 7. Data Collected per Session

Each IoT snapshot includes:

```json
{
  "distance_m": 25.0,
  "temperature_c": 30.2,
  "humidity_percent": 68.5,
  "light_lux": 420.0
}
```

These values are linked to a **shooting session** and stored in the `iot_readings` table.

---

## 8. Academic Justification

Using environmental and range sensors allows the system to:
- separate shooter performance from environmental influence
- support fair comparison between sessions
- improve reliability of AI-based recommendations

This design aligns with **AIoT principles**, where sensor data enhances intelligent system decision-making.

---

## 9. Notes and Best Practices

- Use **short wires** to avoid I2C noise
- Keep sensors away from muzzle blast
- Ensure stable lighting for camera-based scoring
- Calibrate sensors during initial setup

---

## 10. Conclusion

The selected sensors provide a **balanced combination of accuracy, cost, and reliability**, making them suitable for both academic research and practical training environments.

They support the system’s goal of delivering **data-driven, objective, and explainable training feedback**.
