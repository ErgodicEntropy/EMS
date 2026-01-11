<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbName = 'ems_db';

$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (!$mysqli->query("CREATE DATABASE IF NOT EXISTS $dbName")){
    die("Database wasn't created!" . $mysqli->error); 
} else {
    echo "Database has been created successfully!"; 
}

$mysqli->select_db($dbName);

$tables = [];

$tables['department'] = "
CREATE TABLE IF NOT EXISTS department (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL,
    description VARCHAR(255)
) ENGINE=InnoDB;
";

$tables['position'] = "
CREATE TABLE IF NOT EXISTS position (
    position_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    min_salary DECIMAL(10,2),
    max_salary DECIMAL(10,2)
) ENGINE=InnoDB;
";

$tables['employee'] = "
CREATE TABLE IF NOT EXISTS employee (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    hire_date DATE NOT NULL,
    employment_status ENUM('active','suspended','terminated') DEFAULT 'active'
) ENGINE=InnoDB;
";

$tables['employee_position'] = "
CREATE TABLE IF NOT EXISTS employee_position (
    assignment_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL,
    position_id INT NOT NULL,
    department_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE DEFAULT NULL,
    assignment_type ENUM('hire','promotion','transfer') NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employee(employee_id) ON DELETE CASCADE,
    FOREIGN KEY (position_id) REFERENCES position(position_id),
    FOREIGN KEY (department_id) REFERENCES department(department_id)
) ENGINE=InnoDB;
";

$tables['user_account'] = "
CREATE TABLE IF NOT EXISTS user_account (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','hr','manager','employee') NOT NULL,
    employee_id INT UNIQUE,
    FOREIGN KEY (employee_id) REFERENCES employee(employee_id) ON DELETE CASCADE
) ENGINE=InnoDB;
";

$tables['attendance'] = "
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
";

$tables['leave_request'] = "
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
";

$tables['payroll'] = "
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
";

$tables['performance_review'] = "
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
";

foreach ($tables as $name => $sql) {
    if ($mysqli->query($sql)) {
        echo "Table '$name' created successfully.<br>";
    } else {
        echo "Error creating table '$name': " . $mysqli->error . "<br>";
    }
}

$mysqli->close();
echo "<br>EMS setup complete!";
?>
