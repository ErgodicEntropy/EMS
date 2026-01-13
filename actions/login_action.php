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

if ($user && password_verify($password, $user['password_hash'])) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['user_role'];
    header("Location: ../public/home.html");
    exit;
} else {
    echo "Invalid credentials";
}

$stmt->close();
$mysqli->close();

exit; 

?>