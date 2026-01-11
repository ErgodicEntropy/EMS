<?php
require "../config/db.php";

$employee_id = $_POST['employee_id'];
$date = date('Y-m-d');
$time = date('H:i:s');

$stmt = $mysqli->prepare(
    "INSERT INTO attendance (employee_id, date, check_in) VALUES (?, ?, ?)"
);
$stmt->bind_param("iss", $employee_id, $date, $time);

if ($stmt->execute()) {
    echo "Checked in!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>