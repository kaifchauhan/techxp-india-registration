# TechXP India Summit 2026 — Event Registration & Attendee Access Platform

An enterprise-grade event registration, access management, and live attendee portal built for **TechXP India Summit 2026: The Premier AI, Cloud & Innovation Conclave** using **CodeIgniter 4**, **PHP 8.1+**, **MySQL**, **Bootstrap 5.3**, and **Vanilla JavaScript / Fetch API**.

---

## 📌 Project Overview

TechXP India Summit platform fulfills all requirements of a modern high-concurrency event portal:
1. **Visitor Registration**: Clean form with client and server-side validation and responsive ticket pass tiers.
2. **Database Integrity**: Stored in MySQL with prepared statements and query builder.
3. **Duplicate Prevention**: Strict unique constraints and validation rules on both **Email** and **Mobile Number**.
4. **Flexible Attendee Authentication**: Allows login using **either Email OR 10-digit Mobile Number** with Bcrypt verification.
5. **Protected Event Portal**: Guarded by `AuthFilter`; redirects unauthorized visitors with flash alerts and returns to intended destination.
6. **Attendee Profile & Badge**: Displays attendee details, unique badge identifier (`TXP-2026-XXXX`), dynamic QR code, and printable badge.
7. **External Iframe Integration**: Responsive 16:9 keynote stream player embedded directly into the portal.
8. **Real-time Live Chat Component**: Interactive summit chat with auto-polling (every 3.5s), role badges (VIP, Speaker, Admin, Attendee), suggestion chips, and an automated **TechXP Concierge Bot** answering summit queries (Wi-Fi, agenda, lunch).
9. **Real External Third-Party REST API**: Live venue climate and weather forecast for the Bengaluru summit venue powered by **Open-Meteo REST API** (`https://api.open-meteo.com/v1/forecast`), with server-side caching and fallback.
10. **Admin Portal**: Restricted by `AdminFilter` with registration metrics, ticket pass distribution, search/filters, fast 1-click check-in, status management, and **CSV export**.
11. **Clean REST-Style API Endpoints**: Exposes standardized JSON endpoints for registrations, attendee status, summit metrics, venue weather, and chat.
12. **Enterprise Security**: Prepared queries (SQL-injection immune), XSS filtering (`esc()`), CSRF token support, Bcrypt hashing, and structured error handling.

---

## 🚀 Quick Start Guide

### 1. Requirements
- **PHP**: 8.1, 8.2, 8.3, or 8.4 (tested with PHP 8.4)
- **Database**: MySQL 5.7+ / 8.0+ or MariaDB 10.3+
- **PHP Extensions**: `mysqli`, `curl`, `intl`, `mbstring`, `openssl`

### 2. Configure Database & Environment
1. Ensure MySQL is running on `127.0.0.1:3306`.
2. Check or create the `.env` file (copied from `.env.example`):
   ```ini
   CI_ENVIRONMENT = development
   app.baseURL = 'http://localhost:8080/'

   database.default.hostname = 127.0.0.1
   database.default.database = techxp_db
   database.default.username = root
   database.default.password = 
   database.default.DBDriver = MySQLi
   database.default.port = 3306
   ```

### 3. Run Database Migrations & Seeders
Execute the following commands from the project root:
```bash
# Run migrations
php spark migrate

# Seed sample attendees, admin user, and chat messages
php spark db:seed DatabaseSeeder
```
*(Alternatively, you can import `database/schema.sql` directly into MySQL).*

### 4. Start Local Development Server
```bash
php spark serve --port 8080
```
Open **[http://localhost:8080](http://localhost:8080)** in your web browser.

---

## 🔑 Pre-Seeded Test Credentials

| Role | Identifier (Email or Mobile) | Password | Access Area |
| :--- | :--- | :--- | :--- |
| **VIP Attendee** | `aarav.sharma@techcorp.in` *OR* `9876543210` | `Attendee@2026` | [Attendee Portal](http://localhost:8080/event) |
| **Developer Pass** | `priya.patel@innovate.co` *OR* `9812345678` | `Attendee@2026` | [Attendee Portal](http://localhost:8080/event) |
| **Standard Pass** | `rohan.verma@cloudscale.io` *OR* `9988776655` | `Attendee@2026` | [Attendee Portal](http://localhost:8080/event) |
| **Administrator** | `admin` *OR* `admin@techxp.in` | `AdminPassword123!` | [Admin Console](http://localhost:8080/admin) |

---

## 📂 Application Architecture

```
assignment/
├── app/
│   ├── Config/
│   │   ├── Filters.php             # Configures auth and admin filter aliases
│   │   └── Routes.php              # Public, attendee, chat, admin & REST API routes
│   │
│   ├── Controllers/
│   │   ├── Home.php                # Landing page with weather & summit tracks
│   │   ├── AuthController.php      # Email/Mobile dual attendee auth & admin login
│   │   ├── RegistrationController.php # Registration validation, duplicate checks & auto-login
│   │   ├── ProfileController.php   # Protected event portal, live stream & digital badge
│   │   ├── ChatController.php      # Real-time chat polling, messaging & concierge bot
│   │   ├── AdminController.php     # Admin dashboard, table filters, check-in & CSV export
│   │   └── ApiController.php       # Clean REST API endpoints (JSON)
│   │
│   ├── Models/
│   │   ├── RegistrationModel.php   # Attendee registration data, search, stats & check-in
│   │   ├── AdminModel.php          # Admin credentials & login tracking
│   │   └── ChatMessageModel.php    # Live event chat persistence & history
│   │
│   ├── Filters/
│   │   ├── AuthFilter.php          # Guards attendee routes (/event, /badge, /profile)
│   │   └── AdminFilter.php         # Guards administrative routes (/admin/*)
│   │
│   ├── Libraries/
│   │   └── VenueWeatherService.php # Third-Party REST API client (Open-Meteo) with caching
│   │
│   └── Views/
│       ├── layouts/
│       │   ├── header.php          # Meta tags, fonts, Bootstrap 5.3 & custom CSS
│       │   ├── navbar.php          # Dynamic nav with attendee badge dropdown
│       │   └── footer.php          # Footer, quick links, and API links
│       ├── home/index.php          # Landing page with live weather widget
│       ├── auth/
│       │   ├── login.php           # Attendee login (Email OR Mobile supported)
│       │   └── register.php        # Form with validation & pass tiers
│       ├── event/
│       │   ├── index.php           # Protected portal: live stream iframe, attendee info & chat
│       │   └── badge.php           # High-resolution printable digital attendee badge
│       └── admin/
│           ├── login.php           # Staff admin login
│           ├── dashboard.php       # Metrics, ticket distribution & quick actions
│           ├── registrations.php   # Filterable attendee list with 1-click check-in
│           └── registration_details.php # Attendee dossier, status changer & audit trail
│
├── database/
│   └── schema.sql                  # Portable MySQL schema and initial seed data
│
├── public/
│   ├── assets/
│   │   ├── css/app.css             # Glassmorphic cyberpunk indigo theme
│   │   └── js/
│   │       ├── registration.js     # Live client validation, fetch submission & alerts
│   │       ├── login.js            # Smart identifier detection & AJAX login
│   │       ├── chat.js             # Real-time message polling & concierge bot
│   │       └── admin.js            # Fast check-in action & delete confirmation
│   └── index.php
│
├── tests/
│   └── unit/TechXPTest.php         # PHPUnit tests for models, weather API & auth
│
├── .env.example                    # Environment template
└── README.md                       # Comprehensive technical review documentation
```

---

## 🌐 REST API Documentation

All API responses return standardized JSON with HTTP status codes:

### 1. List Registrations
- **Endpoint**: `GET /api/registrations`
- **Query Params**: `search`, `status` (confirmed, attended, cancelled), `ticket_type`, `limit`, `page`
- **Example**:
  ```bash
  curl -s "http://localhost:8080/api/registrations?limit=5"
  ```

### 2. Get Single Registration
- **Endpoint**: `GET /api/registrations/{id_or_badge_id}`
- **Example**:
  ```bash
  curl -s "http://localhost:8080/api/registrations/TXP-2026-1001"
  ```

### 3. Create Registration (REST)
- **Endpoint**: `POST /api/registrations`
- **Payload**:
  ```json
  {
    "full_name": "Dev Sharma",
    "email": "dev.sharma@startup.in",
    "mobile": "9811223344",
    "company": "StartupX",
    "ticket_type": "Developer Pass",
    "password": "Password123!"
  }
  ```
- **Response**: `201 Created` with generated badge ID.

### 4. Update Registration Status
- **Endpoint**: `PATCH /api/registrations/{id}/status`
- **Payload**: `{"status": "attended"}`
- **Response**: `200 OK`

### 5. Summit Stats
- **Endpoint**: `GET /api/stats`
- **Response**:
  ```json
  {
    "status": 200,
    "success": true,
    "data": {
      "total": 6,
      "confirmed": 4,
      "attended": 2,
      "cancelled": 0,
      "ticket_types": [...]
    }
  }
  ```

### 6. Live Venue Weather (Third-Party REST API Proxy)
- **Endpoint**: `GET /api/venue-weather`
- **Response**:
  ```json
  {
    "status": 200,
    "success": true,
    "data": {
      "source": "Open-Meteo REST API (Live)",
      "venue": "TechXP India Arena, Bengaluru",
      "city": "Bengaluru, Karnataka, India",
      "temperature": 26,
      "humidity": 58,
      "condition": "Partly Cloudy"
    }
  }
  ```

### 7. Chat Message Stream
- **Endpoint**: `GET /api/chat/messages?since_id=0`
- **Endpoint**: `POST /api/chat/messages` with `{"message": "Hello summit!"}`

---

## 🧪 Automated Testing

Run the test suite using PHPUnit:
```bash
php vendor/phpunit/phpunit/phpunit --no-coverage tests/unit/TechXPTest.php
```
All 5 test cases and 19 assertions validate ID generation, email/mobile lookup, external REST weather integration, and admin authentication.

---

## 🛡️ Security Implementation
- **SQL Injection Prevention**: All queries use CodeIgniter 4's Query Builder and prepared statements.
- **XSS Sanitization**: Views utilize `esc()` output encoding.
- **CSRF Token Handling**: Configured with token cookies and dynamic headers.
- **Password Security**: Bcrypt (`PASSWORD_BCRYPT`) with modern cost factor.
- **Session Protection**: Route groups protected via `AuthFilter` and `AdminFilter` with strict session checking.
