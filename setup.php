<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbName = 'ems_db';

$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// 1️⃣ Create database if missing
if (!$mysqli->query("CREATE DATABASE IF NOT EXISTS $dbName")) {
    die("Database wasn't created! " . $mysqli->error);
}
$mysqli->select_db($dbName);

// 2️⃣ Create tables safely

// department
$mysqli->query("
CREATE TABLE IF NOT EXISTS department (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL,
    description VARCHAR(255)
) ENGINE=InnoDB;
");

// position
$mysqli->query("
CREATE TABLE IF NOT EXISTS position (
    position_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    min_salary DECIMAL(10,2),
    max_salary DECIMAL(10,2)
) ENGINE=InnoDB;
");

// employee
$mysqli->query("
CREATE TABLE IF NOT EXISTS employee (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    hire_date DATE NOT NULL
) ENGINE=InnoDB;
");

// employee_position
$mysqli->query("
CREATE TABLE IF NOT EXISTS employee_position (
    assignment_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    position_id INT NOT NULL,
    department_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE DEFAULT NULL,
    assignment_type ENUM('hire','promotion','transfer') NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employee(employee_id) ON DELETE CASCADE,
    FOREIGN KEY (position_id) REFERENCES `position`(position_id), 
    FOREIGN KEY (department_id) REFERENCES department(department_id)
) ENGINE=InnoDB;
");

// user_account
$mysqli->query("
CREATE TABLE IF NOT EXISTS user_account (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','hr','manager','employee') NOT NULL,
    employee_id INT UNIQUE,
    FOREIGN KEY (employee_id) REFERENCES employee(employee_id) ON DELETE CASCADE
) ENGINE=InnoDB;
");

// attendance
$mysqli->query("
CREATE TABLE IF NOT EXISTS attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    date DATE NOT NULL,
    check_in TIME,
    check_out TIME,
    worked_hours DECIMAL(5,2),
    status ENUM('present','absent','late','remote') NOT NULL,
    remarks VARCHAR(255),
    FOREIGN KEY (employee_id) REFERENCES employee(employee_id) ON DELETE CASCADE
) ENGINE=InnoDB;
");

// leave_request
$mysqli->query("
CREATE TABLE IF NOT EXISTS leave_request (
    leave_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    leave_type ENUM('annual','sick','unpaid','maternity') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    request_date DATE NOT NULL,
    approval_status ENUM('pending','approved','rejected') DEFAULT 'pending',
    approved_by INT,
    justification VARCHAR(255),
    FOREIGN KEY (employee_id) REFERENCES employee(employee_id),
    FOREIGN KEY (approved_by) REFERENCES employee(employee_id)
) ENGINE=InnoDB;
");

// payroll
$mysqli->query("
CREATE TABLE IF NOT EXISTS payroll (
    payroll_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    pay_period VARCHAR(7) NOT NULL,
    base_salary DECIMAL(10,2) NOT NULL,
    bonuses DECIMAL(10,2) DEFAULT 0,
    deductions DECIMAL(10,2) DEFAULT 0,
    net_salary DECIMAL(10,2) NOT NULL,
    payment_date DATE,
    FOREIGN KEY (employee_id) REFERENCES employee(employee_id)
) ENGINE=InnoDB;
");

// performance_review
$mysqli->query("
CREATE TABLE IF NOT EXISTS performance_review (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    review_period VARCHAR(20),
    performance_score DECIMAL(3,2),
    strengths TEXT,
    weaknesses TEXT,
    recommendations TEXT,
    FOREIGN KEY (employee_id) REFERENCES employee(employee_id),
    FOREIGN KEY (reviewer_id) REFERENCES employee(employee_id)
) ENGINE=InnoDB;
");

echo "EMS database setup complete!";
$mysqli->close();
?>
