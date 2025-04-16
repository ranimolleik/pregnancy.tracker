<?php
session_start();
include("classes/Database.php");

// Check if user is logged in and is a midwife
if (!isset($_SESSION['medical_staff_id']) || $_SESSION['role'] !== 'midwife') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit();
}

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Meal ID is required']);
    exit();
}

$db = new Database();
$meal_id = $_GET['id'];
$midwife_id = $_SESSION['medical_staff_id'];

// Get meal data
$query = "SELECT * FROM meals WHERE id = ? AND created_by = ?";
$meal = $db->read($query, [$meal_id, $midwife_id]);

if (empty($meal)) {
    http_response_code(404);
    echo json_encode(['error' => 'Meal not found']);
    exit();
}

// Return meal data as JSON
header('Content-Type: application/json');
echo json_encode($meal[0]);
?> 