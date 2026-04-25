
# Vulnerable PHP Web Application - Security Demonstration

**Name: KWONG CHI HIN, Isaac**  
**Student ID: 240296882**

---

## Executive Summary

This project demonstrates the design, deployment, exploitation, and remediation of a PHP-based web application containing multiple web vulnerabilities as required for **Task A**.

The application includes both **vulnerable** and **secure** implementations of key functionalities to clearly illustrate common security flaws and their fixes.

**In-scope vulnerabilities:**
- **SQL Injection (SQLi)** – Login and Dashboard
- **Local File Inclusion (LFI)** – View page functionality

---

## Project Structure

```bash
vulnerable-web-app/
├── includes/
├── scripts/
│   ├── start-host-network.ps1
│   └── troubleshoot-ping.ps1
├── secure/
│   └── pages/
│       ├── dashboard.php
│       ├── lfi.php
│       ├── login.php
│       └── logout.php
├── vulnerable/
│   └── pages/
│       ├── dashboard.php
│       ├── lfi.php
│       ├── login.php
│       └── logout.php
├── .dockerignore
├── .env
├── .env.example
├── .gitattributes
├── .gitignore
├── docker-compose.host-network.yml
├── docker-compose.yml
├── docker-entrypoint.sh
├── Dockerfile
├── index.php
└── secure_db_init.sql
```

---

## Features

### Vulnerable Version (`vulnerable/pages/`)
- **Login Page** (`login.php`) – SQL Injection vulnerability
- **Dashboard** (`dashboard.php`) – UNION-based SQL Injection
- **LFI Page** (`lfi.php`) – Local File Inclusion vulnerability


### Secure Version (`secure/pages/`)
Contains patched versions using:
- Prepared statements (PDO)
- Input validation & sanitization
- Whitelisting for file inclusion

---

## Installation & Setup

### Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)

### Setup Steps

1. **Clone or extract the project** to your local machine.

2. **Copy environment configuration**
   ```bash
   cp .env.example .env
   ```

3. **Update `.env` file**
   - Change the web port to `8081` (or your preferred port)
   - Adjust database credentials if needed

4. **Start the application**
   ```bash
   docker compose up -d --build
   ```

5. **Verify containers are running**
   ```bash
   docker ps
   ```

6. **Check database initialization** (optional)
   ```bash
   docker compose logs mysql
   ```

---

## Accessing the Application

After starting the containers, open your browser and go to:

**http://127.0.0.1:8081/**


You can switch between **vulnerable** and **secure** versions by navigating to the respective folders:
- Vulnerable: `/vulnerable/pages/`
- Secure: `/secure/pages/`

---

## Purpose

This project is developed **for educational purposes only** to demonstrate common web application vulnerabilities (SQLi, LFI, XSS) and how to properly remediate them using secure coding practices.

**⚠️ Warning:** The vulnerable version should never be deployed in a production environment or exposed to the internet.

---

## Docker Services

- **PHP + Apache** – Web server
- **MySQL** – Database server

---