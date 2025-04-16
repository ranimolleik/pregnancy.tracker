<?php
session_start();
include("classes/Database.php");
include("classes/Invitation.php");

// Only allow access from localhost for security
if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
    die("Access denied");
}

$invitation = new Invitation();
$token = $invitation->generateInvitationToken();

if ($token) {
    $invitation_url = "http://" . $_SERVER['HTTP_HOST'] . "/apply.php?token=" . $token;
    echo "Invitation URL: " . $invitation_url . "\n";
    echo "Token: " . $token . "\n";
    echo "This token will expire in 24 hours.\n";
} else {
    echo "Failed to generate invitation token.\n";
}
?> 