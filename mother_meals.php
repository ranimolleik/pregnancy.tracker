<?php
// Include mother session configuration at the very beginning
include("mother_session_config.php");
include("classes/Database.php");

// Check if user is logged in and is a mother
if (!isset($_SESSION['mother_id']) || $_SESSION['role'] !== 'mother') {
    header("Location: login.php");
    exit();
}

$db = new Database();

// Get mother's information
$query = "SELECT * FROM mothers WHERE id = ?";
$mother = $db->read($query, [$_SESSION['mother_id']])[0];

// Get mother's complications
$complications = json_decode($mother['complications'] ?? '[]', true);
if (empty($complications)) {
    $complications = ['normal'];
}

// Get meals based on mother's week and complications
$query = "SELECT * FROM meals WHERE week = ? AND (complication IN ('" . implode("','", $complications) . "') OR complication = 'normal') ORDER BY complication";
$meals = $db->read($query, [$mother['pregnancy_week']]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pregnancy Meals</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #fff5f7;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        h1 {
            color: #e91e63;
            margin-bottom: 10px;
        }
        .week-info {
            color: #666;
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        .meal-card {
            background-color: white;
            border: 2px solid #ffcdd2;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .meal-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(233, 30, 99, 0.1);
        }
        .meal-title {
            color: #e91e63;
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .meal-condition {
            display: inline-block;
            background-color: #fff5f7;
            color: #e91e63;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9em;
            margin-bottom: 15px;
        }
        .meal-description {
            color: #666;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .meal-recipe {
            background-color: #fff5f7;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .recipe-title {
            color: #e91e63;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #e91e63;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .no-meals {
            text-align: center;
            color: #666;
            padding: 40px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
        
        <div class="header">
            <h1>Pregnancy Meals</h1>
            <div class="week-info">
                Week <?php echo $mother['pregnancy_week']; ?> of Pregnancy
            </div>
        </div>

        <?php if (empty($meals)): ?>
            <div class="no-meals">
                No meals available for your current pregnancy week and conditions.
                Check back later for updates from your midwife.
            </div>
        <?php else: ?>
            <?php foreach ($meals as $meal): ?>
                <div class="meal-card">
                    <h2 class="meal-title"><?php echo htmlspecialchars($meal['title']); ?></h2>
                    <div class="meal-condition">
                        <?php 
                        $condition_map = [
                            'normal' => 'Normal Pregnancy',
                            'morning_sickness' => 'Morning Sickness',
                            'high_blood_pressure' => 'High Blood Pressure',
                            'gestational_diabetes' => 'Gestational Diabetes',
                            'other' => 'Other Conditions'
                        ];
                        echo $condition_map[$meal['complication']] ?? 'Normal Pregnancy';
                        ?>
                    </div>
                    <div class="meal-description">
                        <?php echo nl2br(htmlspecialchars($meal['description'])); ?>
                    </div>
                    <div class="meal-recipe">
                        <div class="recipe-title">Recipe:</div>
                        <?php echo nl2br(htmlspecialchars($meal['recipe'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html> 