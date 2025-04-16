<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h1>Test Form Submission</h1>";
echo "<pre>";
echo "POST Data:\n";
print_r($_POST);
echo "\n\nGET Data:\n";
print_r($_GET);
echo "\n\nSession Data:\n";
print_r($_SESSION);
echo "</pre>";

// Add a link to go back
echo '<p><a href="login.php">Go back to login</a></p>';
?> 