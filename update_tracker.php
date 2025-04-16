<?php
session_start();
include("classes/Tracker.php");

if (!isset($_SESSION['mother_id'])) {
    header("Location: login.php");
    exit();
}

$mother_id = $_SESSION['mother_id'];
$tracker = new Tracker();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tracker_type = $_POST['tracker_type'];
    
    if ($tracker_type == 'sleep') {
        $hours = floatval($_POST['hours']);
        
        if ($hours >= 0 && $hours <= 24) {
            $tracker->recordSleep($mother_id, $hours);
            $_SESSION['success'] = "Sleep data updated successfully!";
        } else {
            $_SESSION['error'] = "Invalid sleep hours. Please enter a value between 0 and 24.";
        }
    } 
    else if ($tracker_type == 'water') {
        $cups = intval($_POST['cups']);
        
        if ($cups >= 0) {
            $tracker->recordWaterIntake($mother_id, $cups);
            $_SESSION['success'] = "Water intake updated successfully!";
        } else {
            $_SESSION['error'] = "Invalid number of cups. Please enter a positive number.";
        }
    }
}

header("Location: dashboard.php");
exit();
?> 