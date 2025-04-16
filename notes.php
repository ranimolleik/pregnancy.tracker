<?php
// Include mother session configuration at the very beginning
include("mother_session_config.php");
include("classes/Notes.php"); // Include the Notes class

// Check if mother_id is set in the session
if (!isset($_SESSION['mother_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

$mother_id = $_SESSION['mother_id']; // Get the actual mother ID from the session
$notes = (new Notes())->getNotes($mother_id); // Fetch notes for the logged-in mother

// Handle note addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['note_content'])) {
    $noteContent = trim($_POST['note_content']);
    if (!empty($noteContent)) {
        (new Notes())->addNote($mother_id, $noteContent);
        header("Location: notes.php"); // Redirect to the same page to avoid resubmission
        exit();
    }
}

// Handle note deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['note_id'])) {
    $noteId = $_POST['note_id'];
    (new Notes())->deleteNote($noteId); // Call the delete method
    header("Location: notes.php"); // Redirect to the same page to avoid resubmission
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pregnancy Tracker - Notes</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Main Content Area */
        .main-content {
            background-color: #FFF5F7;
            padding: 20px;
            border-radius: 10px;
        }

        /* Header Styling */
        header {
            background-color: #FF4F94;
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .notifications {
            color: white;
        }

        /* Notes Section */
        .notes-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .notes-section h2 {
            color: #FF4F94;
            margin-bottom: 20px;
        }

        /* Note Form */
        #note-input {
            width: 100%;
            padding: 15px;
            border: 2px solid #FFB6C1;
            border-radius: 10px;
            margin-bottom: 15px;
            resize: vertical;
            min-height: 150px;
            font-family: inherit;
        }

        #add-note {
            background-color: #FF4F94;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #add-note:hover {
            background-color: #FF1A75;
        }

        /* Notes List */
        #notes-list {
            list-style: none;
            padding: 0;
        }

        .note-item {
            background-color: #fff;
            border: 1px solid #FFB6C1;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .note-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(255, 79, 148, 0.2);
        }

        .note-text {
            margin-bottom: 10px;
            color: #333;
        }

        .note-date {
            font-size: 0.8em;
            color: #666;
            position: absolute;
            top: 5px;
            right: 10px;
        }

        /* Delete Button */
        .delete-button {
            background-color: #FF4F94;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            transition: background-color 0.3s;
            position: absolute;
            bottom: 10px;
            right: 10px;
        }

        .delete-button:hover {
            background-color: #FF1A75;
        }

        /* Sidebar Styling */
        .sidebar {
            background-color: #FF4F94;
            color: white;
        }

        .sidebar nav a {
            color: white;
            transition: color 0.3s;
        }

        .sidebar nav a:hover {
            color: #FFB6C1;
        }

        .sidebar nav a.active {
            background-color: #FF1A75;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>Pregnancy Tracker</h2>
            <nav>
                <a href="dashboard.php">Home</a>
                <a href="mother_meals.php">Meals</a>
                <a href="mother_exercises.php">Exercises</a>
                <a href="photo_album.php">Album</a>
                <a href="notes.php" class="active">Notes</a>
            </nav>
        </aside>

        <main class="main-content">
            <header>
                <h1>Welcome, <span id="user-name">User</span>!</h1>
                <div class="notifications">
                    <span>🔔</span>
                    <div class="notification-count">2</div>
                </div>
            </header>

            <section class="notes-section">
                <h2>Your Notes for Baby</h2>
                <form method="POST" action="notes.php">
                    <textarea name="note_content" id="note-input" rows="10" placeholder="Write your notes here..." required></textarea>
                    <button type="submit" id="add-note">Add Note</button>
                </form>

                <h3>Your Notes</h3>
                <ul id="notes-list">
                    <?php foreach ($notes as $note): ?>
                        <li class="note-item">
                            <div class="note-text"><?php echo htmlspecialchars($note['content']); ?></div>
                            <div class="note-date"><?php echo htmlspecialchars($note['created_at']); ?></div>
                            <form action="notes.php" method="POST" class="delete-form" style="display:inline;">
                                <input type="hidden" name="note_id" value="<?php echo htmlspecialchars($note['id']); ?>">
                                <button type="submit" class="delete-button">Delete</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        </main>
    </div>

    <script src="script.js"></script>
</body>
</html>