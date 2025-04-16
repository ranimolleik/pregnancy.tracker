<?php
session_start();
include("classes/Admin.php");

// Check if user is logged in as admin


$admin = new Admin();

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'generate_token':
                $token = $admin->generateInvitationToken();
                if ($token) {
                    $_SESSION['success'] = "Invitation token generated successfully!";
                    $_SESSION['token'] = $token;
                } else {
                    $_SESSION['error'] = "Failed to generate invitation token.";
                }
                break;
        }
    }
}

// Get current midwife information if exists
$midwife = $admin->getMidwifeInfo();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .info-box {
            background-color: #e7f3fe;
            border-left: 6px solid #2196F3;
            padding: 15px;
            margin-bottom: 20px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .token-display {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="message success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="message error">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Midwife Information Section -->
        <h2>Midwife Account</h2>
        <?php if (!empty($midwife)): ?>
            <table>
                <tr>
                    <th>Name</th>
                    <td><?php echo htmlspecialchars($midwife[0]['first_name'] . ' ' . $midwife[0]['last_name']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo htmlspecialchars($midwife[0]['email']); ?></td>
                </tr>
                <tr>
                    <th>Registration Date</th>
                    <td><?php echo date('F j, Y', strtotime($midwife[0]['created_at'])); ?></td>
                </tr>
            </table>
        <?php else: ?>
            <div class="info-box">
                No midwife is currently registered in the system.
            </div>
        <?php endif; ?>

        <!-- Invitation Token Section -->
        <h2>Generate Invitation Token</h2>
        <div class="info-box">
            <p>Use this section to generate an invitation token for midwife registration. The token will be valid for 24 hours and can only be used once.</p>
        </div>

        <form method="POST">
            <input type="hidden" name="action" value="generate_token">
            <button type="submit" class="btn">Generate New Token</button>
        </form>

        <?php if (isset($_SESSION['token'])): ?>
            <div class="token-display">
                <h3>Generated Token</h3>
                <p>Token: <strong><?php echo htmlspecialchars($_SESSION['token']); ?></strong></p>
                <p>Invitation URL: <strong>http://<?php echo $_SERVER['HTTP_HOST']; ?>/apply.php?token=<?php echo htmlspecialchars($_SESSION['token']); ?></strong></p>
                <p><em>Note: This token will expire in 24 hours.</em></p>
            </div>
            <?php unset($_SESSION['token']); ?>
        <?php endif; ?>

        <!-- Navigation -->
        <div style="margin-top: 20px;">
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </div>
</body>
</html> 