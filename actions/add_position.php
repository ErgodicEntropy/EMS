<?php
require "../config/db.php";

$title = $_POST['title'];
$base_salary = $_POST['base_salary'];

$stmt = $mysqli->prepare(
    "INSERT INTO position (title, base_salary) VALUES (?, ?)"
);
$stmt->bind_param("sd", $title, $base_salary);

if ($stmt->execute()) {
    header("Location: ../public/home.html");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>