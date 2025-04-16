<?php
// Include midwife session configuration at the very beginning
include("midwife_session_config.php");
include("classes/Database.php");

// Check if user is logged in and is a midwife
if (!isset($_SESSION['medical_staff_id']) || $_SESSION['role'] !== 'midwife') {
    header("Location: login.php");
    exit();
}

$db = new Database();
$midwife_id = $_SESSION['medical_staff_id'];

// Get midwife information
$query = "SELECT * FROM medical_staff WHERE id = ?";
$midwife = $db->read($query, [$_SESSION['medical_staff_id']])[0];

// Get statistics
$query = "SELECT COUNT(*) as total_mothers FROM mothers";
$total_mothers = $db->read($query)[0]['total_mothers'];

// Get unread messages count
$query = "SELECT COUNT(*) as total_messages FROM messages WHERE receiver_id = ? AND sender_role = 'mother'";
$unread_messages = $db->read($query, [$_SESSION['medical_staff_id']])[0]['total_messages'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midwife Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #FFF5F7;
            color: #333;
        }
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #FF4F94;
            color: white;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar h2 {
            margin-bottom: 30px;
            text-align: center;
            color: white;
        }
        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #FF1A75;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #FFF5F7;
        }
        .header {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .welcome {
            color: #FF4F94;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-number {
            font-size: 2em;
            color: #FF4F94;
            margin: 10px 0;
        }
        .action-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .action-card {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        .action-card:hover {
            transform: translateY(-5px);
        }
        .action-card a {
            text-decoration: none;
            color: #333;
        }
        .action-icon {
            font-size: 2em;
            color: #FF4F94;
            margin-bottom: 10px;
        }
        .btn {
            background-color: #FF4F94;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #FF1A75;
        }
        .logout {
            color: #FF4F94;
            text-decoration: none;
            font-weight: bold;
        }
        .logout:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Midwife Dashboard</h2>
            <nav>
                <a href="midwife_dashboard.php" class="active">Home</a>
                <a href="midwife_content.php">Content Management</a>
                <a href="messages.php">Messages</a>
                <a href="manage_mothers.php">Manage Mothers</a>
                <a href="community.php">Community</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="header">
                <div>
                    <h1 class="welcome">Welcome, <?php echo htmlspecialchars($midwife['first_name']); ?>!</h1>
                </div>
                <a href="logout.php" class="logout">Logout</a>
            </div>

            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Mothers</h3>
                    <div class="stat-number"><?php echo $total_mothers; ?></div>
                </div>
                <div class="stat-card">
                    <h3>Messages from Mothers</h3>
                    <div class="stat-number"><?php echo $unread_messages; ?></div>
                </div>
            </div>

            <div class="action-container">
                <div class="action-card">
                    <div class="action-icon">📝</div>
                    <h3>Manage Content</h3>
                    <p>Add and manage meals, exercises, and advice</p>
                    <a href="midwife_content.php" class="btn">Go to Content Management</a>
                </div>
                <div class="action-card">
                    <div class="action-icon">💬</div>
                    <h3>Messages</h3>
                    <p>View and respond to messages from mothers</p>
                    <a href="messages.php" class="btn">View Messages</a>
                </div>
                <div class="action-card">
                    <div class="action-icon">👥</div>
                    <h3>Manage Mothers</h3>
                    <p>View and manage mother profiles</p>
                    <a href="manage_mothers.php" class="btn">Manage Mothers</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html> 