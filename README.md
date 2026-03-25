# 💰 Smart Expense Tracker

A simple yet complete web application to track daily income and expenses, set monthly limits, and visualize spending using charts.

## Tech Stack

![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)


📌 Built as part of my MCA learning journey and Web Development Internship at Edutainer.

---

## 🚀 Tech Stack

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL
- XAMPP (Local Server)

---

## ✨ Features

- 🔐 User authentication (secure login & registration using `password_hash`)
- 📊 Dashboard with:
  - Total Income
  - Total Expense
  - Current Balance
- 🧾 Add & manage transactions:
  - Date, Type, Category, Description, Amount
- 📆 Monthly expense limit tracking
- 📈 Charts using Chart.js:
  - Expense distribution by category
  - Monthly income vs expense trends
- 📤 Export transactions to Excel (CSV format)
- 🔒 Session-based authentication & access control

---

## 📸 Screens

- Login / Register Page
- Dashboard:
  - Summary Cards
  - Expense Limit Form
  - Add Transaction Form
  - Charts (Category & Monthly Trends)
  - Transaction History Table

---

## Screenshots

### Dashboard

![Dashboard](https://github.com/JayanthSD2003/A-php-xampp-MySQL_expense-tracker-dashboard/raw/446506184b50abb83c8df2f6aaf7a6188516b04d/ScreenShots/Screenshot%20(11).png)

### Transaction History / Entries

![Add Transaction](https://github.com/JayanthSD2003/A-php-xampp-MySQL_expense-tracker-dashboard/raw/446506184b50abb83c8df2f6aaf7a6188516b04d/ScreenShots/Screenshot%20(12).png)

### Monthly Limit & Stats

![Monthly Limit](https://github.com/JayanthSD2003/A-php-xampp-MySQL_expense-tracker-dashboard/raw/446506184b50abb83c8df2f6aaf7a6188516b04d/ScreenShots/Screenshot%20(13).png)

### Login UI

![Charts and History](https://github.com/JayanthSD2003/A-php-xampp-MySQL_expense-tracker-dashboard/raw/446506184b50abb83c8df2f6aaf7a6188516b04d/ScreenShots/Screenshot%20(14).png)

### Saved Report on Export

![Report on excel](https://github.com/JayanthSD2003/A-php-xampp-MySQL_expense-tracker-dashboard/blob/6c116972cfcf2b5b81364dc181d77c541e1aed82/ScreenShots/Screenshot%20(15).png)

## 📁 Project Structure

project-root/
          ├── index.php            # Dashboard (requires login)
          ├── login.php            # Login page
          ├── register.php         # Registration page
          ├── logout.php           # Logout logic
          ├── add_transaction.php  # Add new transaction
          ├── save_limit.php       # Manage monthly limit
          ├── export_excel.php     # Export data
          ├── db.php               # Database connection
          ├── auth.php             # Authentication helpers
          ├── main.js              # Frontend logic & charts
          ├── style.css            # Styling
          └── README.md

---

## 🗄️ Database Schema

### Users Table

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

---

### Transactions Table

CREATE TABLE transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tran_date DATE NOT NULL,
  type ENUM('income','expense') NOT NULL,
  category VARCHAR(100) NOT NULL,
  description VARCHAR(255),
  amount DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_id INT
);

---

### Settings Table (Monthly Limit)

CREATE TABLE settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  month_year CHAR(7) NOT NULL UNIQUE,
  expense_limit DECIMAL(10,2) NOT NULL
);

---

## ⚙️ Getting Started (XAMPP Setup)

### 1️⃣ Clone Repository

git clone https://github.com/your-username/smart-expense-tracker.git

---

### 2️⃣ Move to XAMPP Directory

C:\xampp\htdocs\smart-expense-tracker\

---

### 3️⃣ Setup Database

- Open: http://localhost/phpmyadmin  
- Create database: `expense_tracker`
- Run the SQL schema provided above

---

### 4️⃣ Configure Database Connection

Edit `db.php`:

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'expense_tracker';

---

### 5️⃣ Start Server

- Start Apache
- Start MySQL

---

### 6️⃣ Run Application

http://localhost/smart-expense-tracker/login.php

- Register a new user
- Login and start tracking expenses

---

## ⚡ How It Works

### 🔐 Authentication
- Passwords are securely hashed using `password_hash`
- Login uses `password_verify`
- Sessions (`$_SESSION`) manage user login state

---

### 💸 Transactions
- Supports `income` and `expense`
- Data stored per user
- Aggregations done using SQL (`SUM`)

---

### 📆 Monthly Limit
- One record per month
- Displays:
  - Remaining budget
  - Over-limit status

---

### 📊 Charts
Implemented using Chart.js:
- Doughnut Chart → Expense Categories
- Bar Chart → Monthly Income vs Expense

---

## 🔮 Future Improvements

- Per-user monthly limits
- Edit/Delete transactions
- Advanced filtering & reports
- Dark mode 🌙
- REST API for mobile app
- Full-stack upgrade (React / Node / Django)

---

## License

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/JayanthSD2003/A-php-xampp-MySQL_expense-tracker-dashboard/blob/2e50794c2af30fc6ba02bef1c160d585dad11fdd/LICENSE)

This project is licensed under the [MIT License](https://github.com/JayanthSD2003/A-php-xampp-MySQL_expense-tracker-dashboard/blob/main/LICENSE) - see the LICENSE file for details.

