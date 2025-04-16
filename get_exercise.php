<?php
session_start();
include("classes/Database.php");

// Check if user is logged in and is a midwife
if (!isset($_SESSION['medical_staff_id']) || $_SESSION['role'] !== 'midwife') {
    http_response_code(403);
    exit();
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    exit();
}

$db = new Database();
$exercise_id = $_GET['id'];
$midwife_id = $_SESSION['medical_staff_id'];

// Get exercise data
$query = "SELECT * FROM exercises WHERE id = ? AND created_by = ?";
$exercise = $db->read($query, [$exercise_id, $midwife_id]);

if (empty($exercise)) {
    http_response_code(404);
    exit();
}

// Return exercise data as JSON
header('Content-Type: application/json');
echo json_encode($exercise[0]);
?> 