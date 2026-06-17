# 🛺 RikshawHub

RikshawHub is a modern, web-based Auto Rickshaw Booking & Management System designed to connect passengers with local auto-rickshaw drivers. The platform facilitates ride booking, distance/price estimation, real-time status updates, and includes a comprehensive admin dashboard for driver verification and platform monitoring.

---

## 🚀 Key Features

### 👤 Passenger Portal
- **User Registration & Login**: Safe and secure user onboarding.
- **Ride Booking**: Book rides by specifying pickup, destination, and landmark details.
- **Fare Estimation**: Automatic calculation of ride distance and fair pricing.
- **Feedback System**: Leave star ratings and text reviews for drivers after completing rides.

### 🚘 Driver Portal
- **Onboarding & Approval**: Registration request includes submitting vehicle, license, and photo documents.
- **Availability Toggle**: Active/Inactive status toggle to control visibility to passengers.
- **Booking Requests**: Accept or decline incoming ride requests in real-time.
- **Location Updates**: Set and update current location coordinates/names.
- **Ratings & History**: View ratings, customer feedback, and completed trip history.

### 🔑 Admin Portal
- **Driver Verification**: Review, approve, or reject new driver registrations (moderating `temporarydriver` applications).
- **Driver & Passenger Management**: List, inspect, and remove passengers or approved drivers.
- **Booking Monitoring**: View details and live statuses of all platform bookings.
- **System Settings**: Reset admin credentials and manage dashboard views.

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.2 (Apache web server)
- **Database**: MySQL 8.0
- **Frontend**: HTML5, Vanilla CSS3, Vanilla JavaScript
- **Icons & Fonts**: Font Awesome, Remix Icon, Google Fonts (Jost, Outfit)
- **Containerization**: Docker & Docker Compose

---

## 📁 Project Structure

```text
RikshawHub/
├── admin/                 # Admin sub-pages (drivers, passengers, bookings, approvals)
├── assets/                # Images, fonts, and client-side media assets
├── images/                # App-specific UI graphics and icons
├── phpFormSubmit/         # Form processing handlers for PHP
├── scripts/               # JavaScript files for interactive UI and API calls
├── snippets/              # Reusable HTML/PHP frontend components
├── styles/                # CSS stylesheets (modular components, dashboard, login)
├── uploads/               # User-uploaded documents (e.g., driver vehicle photos)
├── Admin.php              # Admin Dashboard main entry point
├── Driver.php             # Driver Dashboard & portal
├── DriverReg.php          # Driver Registration form
├── PassReg.php            # Passenger Registration/Login page
├── about.php              # Platform details and about page
├── adminLog.php           # Admin login page
├── config.php             # Central database connection configuration
├── contact.php            # Customer contact form
├── docker-compose.yml     # Multi-container Docker orchestration script
├── Dockerfile             # Custom Apache/PHP container setup
├── footer.php             # Global page footer component
├── header.php             # Global page header and asset includes
├── index.php              # Website landing page
├── passenger.php          # Passenger Dashboard & booking interface
├── profile.php            # Driver profile update controller
├── profileView.php        # Profile presentation view
└── rikshawhub.sql         # Seed database schema & dummy records
```

---

## ⚙️ Quick Start Guide

### Prerequisites
Make sure you have the following installed on your machine:
- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Installation & Launch

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd RikshawHub
   ```

2. **Spin Up the Environment**
   Launch the Apache and MySQL services in detached mode:
   ```bash
   docker compose up --build -d
   ```

3. **Access the Application**
   Open your browser and navigate to:
   - **User App**: [http://localhost:8085](http://localhost:8085)
   - **Admin Login**: [http://localhost:8085/adminLog.php](http://localhost:8085/adminLog.php)

4. **Shutdown**
   To stop the services, run:
   ```bash
   docker compose down
   ```

---

## 🗄️ Database Configuration & Accounts

The application connects to a MySQL database configured in `config.php` via standard environment variables:
- `DB_HOST`: `db` (configured to default to `localhost` outside of Docker)
- `DB_USER`: `rikshaw_user` (defaults to `root`)
- `DB_PASS`: `rikshaw_pass` (defaults to empty)
- `DB_NAME`: `rikshawhub`

### Pre-seeded Accounts (for Testing)
The database is initialized automatically via `rikshawhub.sql` with some sample credentials:
* **Admin Accounts**:
  - `amalskumar@gmail.com`
  - `aleenamariajames@gmail.com`
  - `joushuamphilip@gmail.com`
  - `riyanshamsudeen@gmail.com`
* **Passenger Account**:
  - `abijit@gmail.com`
* **Driver Account**:
  - `amalskumarofficialz@gmail.com`

---

## 📍 Headquarters & Community
- **Contact**: rikshawhub@gmail.com
- **Copyright**: © 2024 RikshawHub. All rights reserved.
