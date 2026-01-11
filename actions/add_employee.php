<?php
require "../config/db.php";

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$hire_date = $_POST['hire_date'];
$department_id = $_POST['department_id'];
$position_id = $_POST['position_id'];

$sql = "INSERT INTO employee 
(first_name, last_name, email, hire_date, department_id, position_id, status)
VALUES (?, ?, ?, ?, ?, ?, 'active')";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ssssii", $first_name, $last_name, $email, $hire_date, $department_id, $position_id);

if ($stmt->execute()) {
    header("Location: ../public/list_employees.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>