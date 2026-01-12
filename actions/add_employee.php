<?php
require "../config/db.php";

// 1️⃣ Get POST data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$hire_date = $_POST['hire_date'];
$department_id = $_POST['department_id'];
$position_id = $_POST['position_id'];

// 2️⃣ Check for duplicate email
$check = $mysqli->prepare("SELECT 1 FROM employee WHERE email=?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    die("Error: This email already exists in the system.");
}

// 3️⃣ Insert into employee table
$sql = "INSERT INTO employee (first_name, last_name, email, hire_date) VALUES (?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ssss", $first_name, $last_name, $email, $hire_date);

if ($stmt->execute()) {
    // 4️⃣ Get the new employee_id
    $employee_id = $stmt->insert_id;

    // 5️⃣ Insert into employee_position
    $sql2 = "INSERT INTO employee_position 
             (employee_id, position_id, department_id, start_date, assignment_type)
             VALUES (?, ?, ?, ?, 'hire')";
    $stmt2 = $mysqli->prepare($sql2);
    $start_date = $hire_date;
    $stmt2->bind_param("iiis", $employee_id, $position_id, $department_id, $start_date);
    if (!$stmt2->execute()) {
        die("Error inserting employee position: " . $stmt2->error);
    }
    $stmt2->close();

    // 6️⃣ Redirect to employee list
    header("Location: ../public/list_employees.php");
    exit;
} else {
    die("Error inserting employee: " . $stmt->error);
}

$stmt->close();
$mysqli->close();
