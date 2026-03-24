# Election Voting Management System (EVMS)

[![Laravel Version](https://img.shields.io/badge/Laravel-12.x-blue.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2+-red.svg)](https://www.php.net)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![GitHub Repo](https://img.shields.io/badge/GitHub-Repository-blue)](https://github.com)

A comprehensive Laravel-based Election and Voting Management System designed to handle voter eligibility, election 
control, result analytics, and candidate management for students and administrators.

## 📑 Table of Contents
- [About](#about)
- [Key Features](#key-features)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Directory Structure](#directory-structure)
- [Configuration](#configuration)
- [Routes Overview](#routes-overview)
- [Contributing](#contributing)
- [License](#license)
- [Sponsors](#sponsors)

---

## 💻 About

This project is a full-stack Laravel application tailored for **educational or institutional election 
management**. It supports multiple roles including **Admins**, **SAO (System Administrator Officers)**, **Comelec 
Officials**, and **Students**.

Key capabilities include:
*   Student eligibility verification via fingerprint/login.
*   Secure JWT and Sanctum authentication.
*   Election control dashboard with candidate lists.
*   Real-time voting logs and system activity reports.
*   Push notifications via Firebase Cloud Messaging.
*   SMS/Call notifications via Twilio.

---

## ✨ Key Features

| Feature | Description |
| :--- | :--- |
| **Student Portal** | Dashboard, profile update, voting interface, result viewing. |
| **Admin Dashboard** | View system overview, manage users, election control center. |
| **SAO Portal** | Manage candidates, election schedules, and official logs. |
| **Comelec Portal** | Election oversight, result validation, and complaint management. |
| **Reports & Analytics** | Generate PDF reports (using `phpoffice/phpspreadsheet`). |
| **Notifications** | Push (FCM) and SMS (Twilio) alerts. |

---

## 🚀 Prerequisites

Before you begin, ensure you have met the following requirements:

- [Laravel 12](https://laravel.com/docs/introduction) (Requires PHP 8.2+)
- [Composer](https://getcomposer.org/)
- [Node.js & NPM](https://nodejs.org/)
- PHP extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `json`, `xml`, `curl`, `intl`, `bcmath`, 
`ctype`, `fileinfo`, `gd`, `hash`, `imagick`, `zip`.

### `.env` Configuration
You will need to generate an application key and configure your database.

```bash
php artisan key:generate
```

Ensure your `.env` file contains:
```env
DB_CONNECTION=mysql
DB_DATABASE=evms
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Firebase Cloud Messaging
FCM_SERVER_KEY=your_firebase_key
FCM_PROJECT_ID=your_project_id

# Twilio
TWILIO_SID=your_sid
TWILIO_AUTH_TOKEN=your_token
```

---

## 🛠 Installation

1.  **Clone the repository:**
    ```bash
    git clone <repository-url>
    cd ElectionVoting-System
    ```

2.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```

3.  **Install NPM Dependencies:**
    ```bash
    npm install
    npm run build
    ```
    *(Note: Run `npm run watch` for development)*

4.  **Configure Database:**
    Update the `.env` file with your MySQL credentials.
    Run the migrations to set up the schema:
    ```bash
    php artisan migrate
    ```

5.  **Start the Server:**
    ```bash
    php artisan serve
    ```

    Visit `http://localhost:8000` to access the application.

---

## 📂 Directory Structure

The project follows a standard Laravel structure with additional directories for specific logic (e.g., election 
data).

```text
.
├── app
│   ├── Console
│   ├── Exceptions
│   ├── Http
│   │   ├── Controllers
│   │   │   ├── Admin
│   │   │   ├── Api
│   │   │   ├── Auth
│   │   │   ├── Comelec
│   │   │   └── Student
│   │   └── Middleware
│   └── Models
├── bootstrap
├── config
├── database
│   ├── factories
│   ├── migrations
│   └── seeders
├── public
│   └── assets
├── resources
│   ├── views
│   └── views
├── routes
│   ├── api.php
│   └── web.php
└── tests
```

---

## 🔌 Routes Overview

The application is divided into `web` routes (for the dashboard/portal) and `api` routes (for mobile/backend 
consumption).

### Web Routes (`routes/web.php`)

| Route Path | Description | Role |
| :--- | :--- | :--- |
| `GET /login` | Authenticate user | Public |
| `GET /student/dashboard` | Student dashboard | Student |
| `GET /admin/dashboard` | Admin overview | Admin |
| `GET /sao/dashboard` | SAO control panel | SAO |
| `GET /comelec/dashboard` | Comelec oversight | Comelec |
| `GET /reports/analytics` | Generate reports | Admin |
| `POST /student/vote` | Cast a vote | Student |

### API Routes (`routes/api.php`)

| Route Path | Description | Role |
| :--- | :--- | :--- |
| `POST /api/auth/login` | User Login | Public |
| `POST /api/auth/fingerprint` | Biometric Verification | Student |
| `GET /api/candidates` | List Candidates | Student/Admin |
| `GET /api/results` | Get Election Results | Student/Admin |
| `POST /api/notification/send` | Send Twilio/Firebase msg | Admin |

---

## 🤝 Contributing

Contributions are what make the open-source community such an amazing place to learn, inspire, and create. Please 
read the [Contributing Guide](CONTRIBUTING.md) for details on our code of conduct and the process for submitting 
pull requests.

1.  **Fork the Project.**
2.  **Create your Feature Branch** (`git checkout -b feature/AmazingFeature`).
3.  **Commit your Changes** (`git commit -m 'Add some AmazingFeature'`).
4.  **Push to the Branch** (`git push origin feature/AmazingFeature`).
5.  **Open a Pull Request.**

## 📜 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

> "We believe in open-source. Feel free to contribute."

---

## 🌟 Sponsors

<!-- Add logos or names of sponsors here -->

- [Your Sponsor Name]
- [Technology Partner]
- [Institution Support]

*Thank you for supporting open source!*
