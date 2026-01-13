<?php
require "../config/db.php";
session_start();

$employee_id = $_SESSION['user_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$leave_type = $_POST['leave_type'];

$stmt = $mysqli->prepare(
    "INSERT INTO leave_request (employee_id, start_date, end_date, leave_type) VALUES (?, ?, ?, ?)"
);

$stmt->bind_param("isss", $employee_id, $start_date, $end_date, $leave_type);

if ($stmt->execute()) {
    echo "Leave requested";
    header("Location: ../public/home.html");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>