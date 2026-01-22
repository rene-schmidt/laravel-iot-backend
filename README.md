# Laravel IoT Backend

This repository contains a **Laravel-based backend** for ingesting, storing, and serving
telemetry data from embedded and IoT devices.

The project is intentionally kept **simple, robust, and hardware-agnostic** and is designed
to work well with gateways such as **Raspberry Pi** systems collecting data from
microcontrollers (ESP32, STM32, CAN/I2C devices, USB CDC, etc.).

---

## Overview

The backend provides:

- A secured API endpoint for ingesting telemetry data
- Persistent storage of telemetry events
- A polling-friendly API for dashboards and UIs
- A clear separation between ingestion and querying
- A Laravel Blade demo frontend consuming the API

### Typical Data Flow

```
ESP32 / STM32
      ↓
Raspberry Pi Gateway
      ↓
HTTP API (Laravel)
      ↓
Database
      ↓
Frontend (Polling / Socket.IO)
```

---

## Features

- API key–protected telemetry ingestion
- Flexible JSON payloads (no strict schema)
- Automatic server-side timestamps
- Latest-event query endpoint
- UI-friendly empty responses when no data exists
- Ready for CAN, I2C, USB-CDCs

---

## API Endpoints

### Ingest Telemetry

`POST /api/telemetry`

**Headers**
```
X-API-KEY: <your-api-key>
```

**Body (JSON example)**

```json
{
  "source": "raspi-gateway",
  "device": "stm32-node-1",
  "payload": {
    "i2c": 23,
    "can101": "seq=42",
    "can120": "lux=120 full=532 ir=210"
  }
}
```

**Response**

```json
{
  "status": "ok",
  "id": 123
}
```

---

### Query Latest Telemetry

`GET /api/telemetry/latest`

**Response (example)**

```json
{
  "status": "ok",
  "data": {
    "id": 123,
    "source": "raspi-gateway",
    "device": "stm32-node-1",
    "received_at": "2026-01-22T10:15:30Z",
    "i2c": 23,
    "can101": "seq=42",
    "can120": "lux=120 full=532 ir=210"
  }
}
```

If no telemetry data exists yet, the API returns a predictable, UI-safe empty structure.

---

## Database

Telemetry data is stored in the `telemetry_events` table.

**Columns**

- `id` – primary key
- `source` – data source (nullable)
- `device` – device identifier (nullable)
- `payload` – JSON telemetry payload
- `created_at`, `updated_at`

The `payload` column is fully flexible and stored as JSON.

---

## Configuration

### Required Environment Variables

- `IOT_API_KEY` – API key required for telemetry ingestion

**Example**

```env
IOT_API_KEY=your-secret-key
```

---

## Development Setup

1. Install dependencies  
   ```bash
   composer install
   ```

2. Create environment file  
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Configure database credentials in `.env`

4. Run migrations  
   ```bash
   php artisan migrate
   ```

5. Start the development server  
   ```bash
   php artisan serve
   ```

---

## Notes

- The backend is intentionally polling-friendly
- No strict payload validation is enforced server-side
- Payload parsing is handled in the frontend
- Socket.IO CDC streaming runs independently from the Laravel API

---

## License

This project is provided **as-is** for educational and experimental purposes.
