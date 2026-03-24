# 💰 Smart Expense Tracker

A simple yet complete web application to track daily income and expenses, set monthly limits, and visualize spending using charts.

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

## 📄 License

This project is built for learning and portfolio purposes.  
Feel free to use and modify it for educational use.
