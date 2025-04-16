<?php
// Only start session if it is not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Retrieve messages from session
$success_message = isset($_SESSION['success']) ? $_SESSION['success'] : "";
$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : "";

// Retrieve form data if available (to retain values after error)
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

// Clear messages after displaying
unset($_SESSION['success']);
unset($_SESSION['error']);
unset($_SESSION['form_data']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pregnancy Tracker - Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to Pregnancy Tracker</h1>

        <!-- Show success message -->
        <?php if (!empty($success_message)): ?>
            <p style="color: green; text-align: center;"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <!-- Show error message -->
        <?php if (!empty($error_message)): ?>
            <p style="color: red; text-align: center;"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form id="signup-form" action="classes/signup.php" method="POST">
            <label for="first-name">First Name</label>
            <input type="text" id="first-name" name="first_name" required value="<?php echo isset($form_data['first_name']) ? htmlspecialchars($form_data['first_name']) : ''; ?>">

            <label for="last-name">Last Name</label>
            <input type="text" id="last-name" name="last_name" required value="<?php echo isset($form_data['last_name']) ? htmlspecialchars($form_data['last_name']) : ''; ?>">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : ''; ?>">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="pregnancy-start">Pregnancy Start Date</label>
            <input type="date" id="pregnancy-start" name="pregnancy_start" required value="<?php echo isset($form_data['pregnancy_start']) ? htmlspecialchars($form_data['pregnancy_start']) : ''; ?>">

            <fieldset>
                <legend>Do you have any pregnancy problems?</legend>
                <label>
                <input type="checkbox" name="problems[]" value="no normal" 
                    <?php echo (isset($form_data['problems']) && in_array("normal", $form_data['problems'])) ? "checked" : ""; ?>> Normal
                     </label><br>
                <label>
                    <input type="checkbox" name="problems[]" value="heart problems" 
                    <?php echo (isset($form_data['problems']) && in_array("morning_sickness", $form_data['problems'])) ? "checked" : ""; ?>> Morning Sickness
                </label><br>
                <label>
                    <input type="checkbox" name="problems[]" value="high_blood_pressure" 
                    <?php echo (isset($form_data['problems']) && in_array("high_blood_pressure", $form_data['problems'])) ? "checked" : ""; ?>> High Blood Pressure
                </label><br>
                <label>
                    <input type="checkbox" name="problems[]" value="gestational_diabetes" 
                    <?php echo (isset($form_data['problems']) && in_array("gestational_diabetes", $form_data['problems'])) ? "checked" : ""; ?>> Gestational Diabetes
                </label><br>
                <label>
                    <input type="checkbox" name="problems[]" value="other"> Other
                </label>
                    <input type="text" name="other_problems" placeholder="Please specify" style="display: inline; width: 200px;">
            </fieldset>

            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Log In</a></p>
    </div>
</body>
</html>
