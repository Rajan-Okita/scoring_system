# Judging System - LAMP Stack Web Application

## Overview 
This is a simple web application built on the LAMP (Linux, Apache, MySQL, PHP) stack. It allows pre-registered judges to assign scores to participants, view them in real time, and manage users from an admin dashboard.

## Setup Instructions
### 1. Requirements
- PHP >= 7.4

- MySQL >= 5.7 (8.0+ recommended)

- Apache web server 

- A local server environment like XAMPP

### 2. Install XAMPP
1. Download XAMPP from https://www.apachefriends.org.

2. Run the installer and follow the on-screen instructions.

3. Launch the XAMPP Control Panel and start Apache and MySQL.

4. Place the project folder inside the htdocs directory (usually found in C:/xampp/htdocs).

### 2. Clone the Repository 
1. Ensure git is installed by checking 
``` bash 
git --version
```
2. If not installed, download it from https://git-scm.com/downloads and follow the instructions

3. Open terminal and change directory to new folder created at step 4 then copy the following commands:
``` bash
git clone https://github.com/Rajan-Okita/scoring_system.git
```
``` bash 
cd scoring_system
```

### 3. Configure Database 
- Open XAMPP control panel and confirm Apache and MySQL have started.
- Click the admin option aligned with mySQL.
- Click the New icon for database once in phpMyAdmin.
- Input a name for the database e.g. judging_system
- Once created, click sql and run the following sql statements
#### Database Schema 
```
CREATE TABLE users (
    users_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    score INT(11) DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()
);

INSERT INTO users 
(users_id, name, score) VALUES
('', 'James Omondi','30'),
('', 'Peter ','10'),
('', 'Gibson Viol','60'),
('', 'Gift','40'),
('', 'Steve','80'),
('', 'Winnie',''),
('', 'Joy kitha','25'),
('', 'Wendy Wangechi','55'),
('', 'Japheth Okong','99'),
('', 'James Kiama','50'),
('', 'Jane Wanjiku','70'),
('','Peter Michael','');

CREATE TABLE admin (
    admin_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email_address VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO admin
(admin_id, first_name, last_name, email_address, password) VALUES
(1, 'Admin', '', 'admin@gmail.com', '$2y$10$M0okiwSsNgqG7yfr1lWeVOgWBWevfrguxrHAL02rjPq6w1XCheEwO');

CREATE TABLE judges (
    judges_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email_address VARCHAR(100) UNIQUE NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL
);
```
### 4. Setup Authentication 
Update auth/connection.php with your local DB credentials:
```
$servername = "localhost";
$username = "root";
$password = "";
$database = "input own name used";
```
### 5. Run the Application
- Open **http://localhost/folder created/scoring_system/index.php** in your browser
- Input Admin email as **admin@gmail.com** and password as **admin123456** to log in to admin dashboard
- Add a new judge and use the credentials to login to the judge portal 
- View scores of users and edit their scores
- You can also view the public scoreboard to see a list of users and their scores 

## Assumptions 
- Admins are added manually and login only (no registration).
- Judges are created by the admin and can login to assign scores.
- Participants are manually entered or batch-imported by the admin.
- Each participant has one unified score field that can be updated.
- Scores can be updated by any Judge hence no need to track which judge changed scores for an individual.

## Design Decisions 
- Users score: Flattened the scoring model to store scores in the users table directly, simplifying retrieval.

- AJAX + Long Polling: Used vanilla JavaScript and long polling for real-time updates without full page reloads.

- Prepared Statements: Used mysqli_prepare() and bind_param() to protect against SQL injection.

## Future Features 
- WebSockets for real-time updates (replace long polling for better efficiency and responsiveness)
- Authentication with session handling for judges and admins
- Score history tracking per judge and participant
- Export scoreboard as CSV or PDF
- Implementation of sessions and logout from the system