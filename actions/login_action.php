<?php
session_start();
require "../config/db.php";

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $mysqli->prepare("SELECT * FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];
    header("Location: ../dashboard.php");
    exit;
} else {
    echo "Invalid credentials";
}

$stmt->close();
$mysqli->close();

header("Location: ../public/home.html");
exit; 

?>