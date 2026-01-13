<?php
session_start();
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     die("Access denied");
// }

require "../config/db.php"; 

$username = $_POST['username'];
$passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];
$employee_id = $_POST['employee_id'];


$check = $mysqli->prepare("SELECT * FROM employee WHERE employee_id = ?");
$check->bind_param("i", $employee_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    die("Cannot create user: employee does not exist.");
}

$stmt = $mysqli->prepare(
    "INSERT INTO user (username, password_hash, user_role, employee_id) VALUES (?, ?, ?, ?)"
);

if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}

$stmt->bind_param("sssi", $username, $passwordHash, $role, $employee_id);

if ($stmt->execute()) {
    echo "User account created successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();

header("Location: ../public/home.html");
exit; 
?>