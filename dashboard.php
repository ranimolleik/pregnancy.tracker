<?php
// Include mother session configuration at the very beginning
include("mother_session_config.php");

// Include Tracker class
include("classes/Tracker.php");

// Initialize tracker object
$tracker = new Tracker();

// Check if user is logged in and is a mother
if (!isset($_SESSION['mother_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['mother_id'];

// Get mother's information
$mother_result = $tracker->getDb()->read("SELECT first_name, last_name, complications FROM mothers WHERE id = ?", [$user_id]);

if (empty($mother_result)) {
    // Handle case where mother data is not found
    header("Location: login.php?error=User data not found");
    exit();
}

$mother = $mother_result[0];
$complications = json_decode($mother['complications'] ?? '[]', true);
$complication = in_array('normal', $complications) ? 'normal' : 'high_risk';

// Get pregnancy progress
$progress = $tracker->getPregnancyProgress($user_id);
$weekly_summary = $tracker->getWeeklySummary($user_id);

// Get today's data
$today_sleep = $tracker->getSleepData($user_id);
$today_water = $tracker->getWaterIntake($user_id);

// Calculate due date (40 weeks from pregnancy start)
$due_date = date('Y-m-d', strtotime($progress['start_date'] . ' + 40 weeks'));

// Get weekly content
$current_advice = $tracker->getWeeklyAdvice($progress['weeks']) ?? "Enjoy your pregnancy journey!";
$weekly_meals = $tracker->getWeeklyMeals($progress['weeks'], $complication);
$weekly_exercises = $tracker->getWeeklyExercises($progress['weeks'], $complication);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pregnancy Tracker - Dashboard</title>
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
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 15px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .welcome-message {
            font-size: 24px;
            color: #FF4F94;
            margin: 0;
        }
        .header-actions a {
            color: #FF4F94;
            text-decoration: none;
            margin-left: 20px;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .header-actions a:hover {
            background-color: #FFB6C1;
        }
        .tracker-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .tracker-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .tracker-card:hover {
            transform: translateY(-5px);
        }
        .progress-bar {
            width: 100%;
            height: 40px;
            background-color: #FFB6C1;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
            position: relative;
        }
        .progress {
            height: 100%;
            background-color: #FF4F94;
            transition: width 0.3s ease;
        }
        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
            z-index: 1;
        }
        .water-intake {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }
        .water-cup {
            color: #FF4F94;
            font-size: 24px;
        }
        .countdown-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding: 15px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .countdown-item {
            text-align: center;
            padding: 10px;
            background: #FF4F94;
            color: white;
            border-radius: 5px;
            min-width: 80px;
        }
        .countdown-number {
            font-size: 24px;
            font-weight: bold;
        }
        .countdown-label {
            font-size: 12px;
            text-transform: uppercase;
        }
        .weekly-advice {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin: 20px 0;
        }
        .weekly-advice h3 {
            color: #FF4F94;
            margin-bottom: 10px;
        }
        .weekly-advice p {
            font-size: 16px;
            line-height: 1.5;
        }
        .weekly-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .content-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .content-card:hover {
            transform: translateY(-5px);
        }
        .content-card h3 {
            color: #FF4F94;
            margin-bottom: 15px;
        }
        .content-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #FFB6C1;
        }
        .content-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .content-image {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin: 10px 0;
        }
        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            margin: 10px 0;
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #FF4F94;
        }
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #FFB6C1;
            border-radius: 5px;
            font-size: 16px;
        }
        input[type="number"]:focus {
            outline: none;
            border-color: #FF4F94;
        }
        button {
            background-color: #FF4F94;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #FF1A75;
        }
        .weekly-summary {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #FFB6C1;
        }
        .weekly-summary h4 {
            color: #FF4F94;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Pregnancy Tracker</h2>
            <nav>
                <a href="dashboard.php" class="active">Home</a>
                <a href="mother_meals.php">Meals</a>
                <a href="mother_exercises.php">Exercises</a>
                <a href="photo_album.php">Photo Album</a>
                <a href="notes.php">Notes</a>
                <a href="community.php">Community</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header>
                <div class="welcome-message">
                    Welcome, <?php echo htmlspecialchars($mother['first_name'] . ' ' . $mother['last_name']); ?>!
                </div>
                <div class="header-actions">
                    <a href="#" id="account-link">Account</a>
                    <a href="logout.php" id="logout-link">Logout</a>
                </div>
            </header>

            <!-- Pregnancy Progress Section -->
            <section class="progress-section">
                <h2>Pregnancy Progress</h2>
                <div class="progress-container">
                    <div class="progress-bar">
                        <div class="progress" style="width: <?php echo $progress['percentage']; ?>%;"></div>
                        <div class="progress-text">Week <?php echo $progress['weeks']; ?>, Day <?php echo $progress['days']; ?></div>
                    </div>
                    <div class="countdown-container">
                        <div class="countdown-item">
                            <div class="countdown-number" id="days">00</div>
                            <div class="countdown-label">Days</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-number" id="hours">00</div>
                            <div class="countdown-label">Hours</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-number" id="minutes">00</div>
                            <div class="countdown-label">Minutes</div>
                        </div>
                        <div class="countdown-item">
                            <div class="countdown-number" id="seconds">00</div>
                            <div class="countdown-label">Seconds</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Weekly Content Section -->
            <section class="weekly-content">
                <!-- Weekly Advice -->
                <div class="content-card">
                    <h3>Weekly Advice</h3>
                    <div class="content-item">
                        <p><?php echo htmlspecialchars($current_advice); ?></p>
                    </div>
                </div>

                <!-- Weekly Meals -->
                <div class="content-card">
                    <h3>Recommended Meals</h3>
                    <?php foreach ($weekly_meals as $meal): ?>
                        <div class="content-item">
                            <h4><?php echo htmlspecialchars($meal['title']); ?></h4>
                            <p><?php echo htmlspecialchars($meal['description']); ?></p>
                            <?php if ($meal['recipe']): ?>
                                <p><strong>Recipe:</strong> <?php echo htmlspecialchars($meal['recipe']); ?></p>
                            <?php endif; ?>
                            <?php if ($meal['image']): ?>
                                <img src="<?php echo htmlspecialchars($meal['image']); ?>" alt="<?php echo htmlspecialchars($meal['title']); ?>" class="content-image">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Weekly Exercises -->
                <div class="content-card">
                    <h3>Recommended Exercises</h3>
                    <?php foreach ($weekly_exercises as $exercise): ?>
                        <div class="content-item">
                            <h4><?php echo htmlspecialchars($exercise['title']); ?></h4>
                            <p><?php echo htmlspecialchars($exercise['description']); ?></p>
                            <?php if ($exercise['image']): ?>
                                <img src="<?php echo htmlspecialchars($exercise['image']); ?>" alt="<?php echo htmlspecialchars($exercise['title']); ?>" class="content-image">
                            <?php endif; ?>
                            <?php if ($exercise['video_url']): ?>
                                <div class="video-container">
                                    <iframe src="<?php echo htmlspecialchars($exercise['video_url']); ?>" allowfullscreen></iframe>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Trackers Section -->
            <section class="tracker-container">
                <!-- Sleep Tracker -->
                <div class="tracker-card">
                    <h3>Sleep Tracker</h3>
                    <form action="update_tracker.php" method="POST">
                        <input type="hidden" name="tracker_type" value="sleep">
                        <div class="form-group">
                            <label for="sleep_hours">Hours Slept:</label>
                            <input type="number" id="sleep_hours" name="hours" step="0.5" min="0" max="24" 
                                   value="<?php echo $today_sleep ? $today_sleep['sleep_hours'] : ''; ?>" required>
                        </div>
                        <button type="submit">Update Sleep</button>
                    </form>
                    <?php if ($weekly_summary): ?>
                        <div class="weekly-summary">
                            <h4>Weekly Average</h4>
                            <p>Sleep Hours: <?php echo round($weekly_summary['avg_sleep_hours'], 1); ?> hours</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Water Tracker -->
                <div class="tracker-card">
                    <h3>Water Intake</h3>
                    <form action="update_tracker.php" method="POST">
                        <input type="hidden" name="tracker_type" value="water">
                        <div class="form-group">
                            <label for="water_cups">Number of Cups:</label>
                            <div class="water-intake">
                                <i class="fas fa-glass-water water-cup"></i>
                                <input type="number" id="water_cups" name="cups" min="0" step="1" 
                                       value="<?php echo $today_water ? $today_water['water_cups'] : ''; ?>" required>
                            </div>
                        </div>
                        <button type="submit">Add Water</button>
                    </form>
                    <?php if ($weekly_summary): ?>
                        <div class="weekly-summary">
                            <h4>Weekly Total</h4>
                            <p>Water Intake: <?php echo $weekly_summary['total_water_cups']; ?> cups</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Update countdown timer
        function updateCountdown() {
            const dueDate = new Date('<?php echo $due_date; ?>').getTime();
            const now = new Date().getTime();
            const distance = dueDate - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').textContent = days.toString().padStart(2, '0');
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        }

        // Update countdown every second
        setInterval(updateCountdown, 1000);
        updateCountdown(); // Initial call
    </script>
</body>
</html>