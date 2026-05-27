# TECHNOLOGY-PROJECT
# 🔐 PassGuard

**A pedagogical password vault that teaches good security habits.**

PassGuard is a web-based personal password vault that lets users store credentials securely and provides real-time feedback on password strength and overall security health. Built as a first-year capstone project combining cybersecurity, web development, and data visualization.

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [Team & Roles](#team--roles)
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
  - [Database Setup](#database-setup)
- [Test Accounts](#test-accounts)
- [Usage Guide](#usage-guide)
- [Security Considerations](#security-considerations)
- [Lessons Learned](#lessons-learned)
- [License](#license)

---

## Features

### Core (MVP)

- **Master password authentication** — account creation, login, logout, and session timeout. Master password hashed with `password_hash()` (bcrypt).
- **Full CRUD on credentials** — add, view, edit, and delete stored credentials (site name, URL, username, password).
- **Password strength scoring** — every saved password is scored based on length, character variety, and presence in a top-1000 common passwords list (sourced from RockYou).
- **Security dashboard** — overall vault health score (0–100), with a breakdown of weak, medium, and strong passwords.

### Bonus

- **Password generator** — configurable length and character classes (uppercase, lowercase, digits, symbols), integrated directly into the "Add credential" form, with a copy-to-clipboard button.
- **Reuse detection** — flags duplicated passwords across the vault.
- **Weak password detection** — checks every stored password against a real common-password list (top 1,000+).
- **Distribution chart** — visual breakdown of password strength across the vault using Chart.js.
- **Prioritized recommendations** — list view highlighting weak or duplicated passwords with actionable suggestions.

---

## Tech Stack

| Layer          | Technology                        |
|----------------|-----------------------------------|
| Front-end      | HTML, CSS, JavaScript             |
| Back-end       | PHP 8.3.28                         |
| Database       | MySQL 8.4.7                        |
| Charts         | Chart.js                          |
| Version Control| Git / GitHub                      |

---

## Architecture

```
┌──────────────────────────────────────────────────────┐
│                      Client                          │
│   HTML/CSS/JS  ·  Chart.js  ·  Password Generator    │
└──────────────┬───────────────────────┬───────────────┘
               │  HTTP (forms / fetch) │
┌──────────────▼───────────────────────▼───────────────┐
│                   PHP Back-end                        │
│   Authentication · Session mgmt · Business logic      │
│   Strength scoring · CRUD API · Common-password check │
└──────────────┬───────────────────────┬───────────────┘
               │       PDO / MySQLi    │
┌──────────────▼───────────────────────▼───────────────┐
│                   MySQL Database                      │
│   users · credentials · password_scores               │
└──────────────────────────────────────────────────────┘
```

---

## Team & Roles

| Role                      | Member          |
|---------------------------|-----------------|
| Project Lead / Integrator | Victor Jeff        |
| Cybersecurity Lead        | Victor Jeff        |
| Front-end Developer       | Victor Jeff and Lekane Carel        |
| Back-end Developer        | Romain        |
| Database & Queries        | Romain       |
| Data Visualization        | Leroy and Lekane Carel       |

---

## Getting Started

### Prerequisites

- **PHP** ≥ 8.0
- **MySQL** ≥ 8.0 or **MariaDB** ≥ 10.4
- **Apache** with `mod_rewrite` enabled (or any PHP-capable web server)
- A local development environment such as **XAMPP**, **WAMP**, **MAMP**, or **Laragon**

### Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/<your-org>/passguard.git
   cd passguard
   ```

2. **Configure the database connection**

   Copy the sample config and fill in your credentials:

   ```bash
   cp config/db.example.php config/db.php
   ```

   Edit `config/db.php`:

   ```php
   <?php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'passguard');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

3. **Place the project in your web server's document root**

   For XAMPP: copy the `passguard/` folder into `htdocs/`.

4. **Start Apache and MySQL** from your local environment's control panel.

5. **Open the app** at http://localhost/passguard/passguard/php/login.php

### Database Setup

Import the provided SQL script to create the schema and insert sample data:

```bash
mysql -u root -p < database/passguard.sql
```

Or via phpMyAdmin: create a database named `passguard`, then import `database/passguard.sql`.

The script will:
- Create all required tables (`users`, `credentials`, etc.)
- Insert sample data for demonstration purposes
- Populate the common-passwords reference table

---

## Test Accounts

Use these accounts to explore the application during the demo:

| Account       | Email / Username       | Master Password   | Notes                                |
|---------------|------------------------|--------------------|--------------------------------------|
| Demo User 1   | `alice@example.com`   | `DemoPass!2025`    | Vault with mixed-strength passwords  |
| Demo User 2   | `bob@example.com`     | `SecureDemo#99`    | Vault with mostly weak passwords     |
| Admin (if any) | `admin@example.com`  | `Admin!Demo2025`   | For testing admin features           |

> ⚠️ **This is a learning project. Do not store real passwords in PassGuard.**

---

## Usage Guide

### 1. Create an Account
Navigate to the registration page and set a strong master password. The app will show you the strength of your chosen master password in real time.

### 2. Log In
Enter your master password. After a period of inactivity, your session will time out automatically for security.

### 3. Add a Credential
Click **"Add"** and fill in the site name, URL, username, and password. Use the built-in **password generator** to create a strong password instantly, then copy it to your clipboard.

### 4. View Your Dashboard
The dashboard displays your overall vault health score (0–100) and a Chart.js doughnut/bar chart showing the distribution of weak, medium, and strong passwords. Reused or common passwords are flagged with recommendations.

### 5. Improve Your Security
Follow the prioritized recommendations to replace weak or duplicated passwords. Watch your vault score improve in real time.

---

## Security Considerations

- **Master password hashing** — stored using PHP's `password_hash()` with the bcrypt algorithm. Plain-text passwords are never stored.
- **Session management** — sessions are regenerated on login, destroyed on logout, and expire after a configurable timeout.
- **Prepared statements** — all SQL queries use PDO prepared statements to prevent SQL injection.
- **Input validation & sanitization** — all user input is validated server-side and escaped on output to prevent XSS.
- **Common-password detection** — passwords are checked against a curated list of the top 1,000 most common passwords (RockYou dataset).
- **CSRF protection** — forms include a CSRF token to prevent cross-site request forgery.
- **HTTPS recommended** — in a production environment, all traffic should be served over HTTPS.

> **Disclaimer:** PassGuard is a pedagogical project. It is not a production-grade password manager. Stored credentials are protected by hashing and access control, but the application has not undergone a professional security audit.

---

## Lessons Learned

> - IMPROVEMENT IN PHP LANGUAGE
> - SMARTY CONNECTIONS
> - GIT MANAGEMENT

---

## License

This project was built as part of a first-year Technology Project course. It is intended for educational purposes only.
