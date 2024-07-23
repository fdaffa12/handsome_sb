<?php
// Include the database configuration file
include 'config.php';

// Get the NIK from the GET request
$nik = $_GET['nik'];

// Prepare and bind
$stmt = $conn->prepare("SELECT COUNT(*) FROM hs_employee WHERE NIK = ?");
$stmt->bind_param("s", $nik);

// Execute the query
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();

$stmt->close();
$conn->close();

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode(array("valid" => $count > 0));
?>
