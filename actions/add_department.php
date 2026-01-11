<?php
require "../config/db.php";

$department_name = $_POST['department_name'];
$description = $_POST['description'];

$stmt = $mysqli->prepare(
    "INSERT INTO department (department_name, description) VALUES (?, ?)"
);
$stmt->bind_param("ss", $department_name, $description);

if ($stmt->execute()) {
    header("Location: ../dashboard.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>