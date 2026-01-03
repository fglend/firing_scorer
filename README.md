# AIoT-Enabled Electronic Target Scoring System

This project is an **AIoT-based firing range training system** that integrates **computer vision, IoT sensors, machine learning analytics, and a web-based dashboard** to automatically score shooting targets and provide data-driven training feedback.

The system is designed for **academic research, training environments, and performance evaluation**, with emphasis on **objectivity, consistency, and explainability**.

---

## 1. Project Overview

Traditional target scoring in firing ranges is often:
- manual and time-consuming
- prone to human error
- limited in feedback quality

This project addresses these limitations by combining:
- **Image-based shot detection**
- **IoT environmental sensing**
- **Automated scoring logic**
- **AI-generated training recommendations**

All results are presented in a **real-time web dashboard** built with Laravel, Livewire, and Filament.

---

## 2. System Objectives

The main objectives of this project are to:

1. Automatically detect bullet holes on targets
2. Compute shot scores accurately and consistently
3. Record environmental and range conditions using IoT sensors
4. Analyze shot patterns and performance trends
5. Generate meaningful training recommendations
6. Provide an intuitive dashboard for trainers and administrators

---

## 3. System Architecture (High-Level)

The system consists of four major components:

### 3.1 IoT Layer
- ESP32-based devices
- Distance, temperature, humidity, and light sensors
- Periodic data transmission via HTTP (JSON)

### 3.2 Computer Vision & Scoring
- Target image acquisition
- Bullet hole detection
- Coordinate mapping
- Distance-from-center computation
- Rule-based scoring per shot

### 3.3 Backend & Analytics
- Laravel REST API
- Relational database (sessions, shots, IoT readings)
- Machine learning–assisted pattern analysis
- Recommendation generation logic

### 3.4 Dashboard & Visualization
- Filament (admin management)
- Livewire (trainer-facing dashboard)
- SVG-based charts and heatmaps
- Exportable reports (CSV, JSON, PDF-ready)

---

## 4. Key Features

- 🎯 Automatic shot detection and scoring
- 🌡️ IoT-based environmental monitoring
- 📊 Interactive dashboard with charts and heatmaps
- 🧠 AI-driven training recommendations
- 📁 Session-based data organization
- 📤 Export to CSV and JSON
- 🖨️ Print-friendly session reports (PDF-ready)

---

## 5. Technology Stack

### Hardware
- ESP32 DevKit / ESP32-CAM
- VL53L0X (Distance)
- BME280 / SHT31 (Temperature & Humidity)
- BH1750 (Light Intensity)

### Software
- Laravel (Backend framework)
- Livewire (Reactive UI)
- Filament (Admin panel)
- MySQL / PostgreSQL (Database)
- Arduino IDE (ESP32 programming)

---

## 6. Database Design (Core Models)

- `shooting_sessions` – training sessions
- `targets` – target metadata
- `shots` – detected bullet holes and scores
- `iot_readings` – sensor data
- `recommendations` – AI-generated feedback

Scores are **derived from `shots.score`**, ensuring consistency and traceability.

---

## 7. Dashboard Design

### Filament (Admin)
- Session management
- Data inspection
- Debugging and verification

### Livewire (Trainer Dashboard)
- Session overview
- IoT trend charts
- Shot clustering heatmap
- AI recommendations
- Export and reporting tools

---

## 8. Data Flow Summary

1. ESP32 collects sensor data
2. Data is sent to Laravel API
3. Images are processed for shot detection
4. Scores are computed per shot
5. Session metrics are derived
6. AI recommendations are generated
7. Results are displayed on the dashboard

---

## 9. Installation Overview

> Detailed setup guides are provided in separate README files.

High-level steps:
1. Set up Laravel backend
2. Run database migrations
3. Configure ESP32 devices
4. Start Laravel server
5. Access dashboard via browser

---

## 10. Academic Contribution

This project demonstrates:
- Practical application of AIoT concepts
- Integration of hardware and software systems
- Explainable performance analytics
- Data-driven training evaluation

It is suitable for:
- Capstone projects
- Applied AI research
- Training system modernization studies

---

## 11. Future Enhancements

- Deep learning–based shot detection
- Real-time video streaming
- Mobile dashboard support
- Cloud-based deployment
- Advanced statistical analysis

---

## 12. License & Usage

This project is intended for **educational and research purposes**.  
Commercial deployment may require additional validation and safety review.

---

## 13. Author

Developed as part of an academic research and system development project.

---

**End of README**
