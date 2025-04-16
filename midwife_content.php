<?php
include("midwife_session_config.php");
include("classes/Database.php");

// Check if user is logged in and is a midwife
if (!isset($_SESSION['medical_staff_id']) || $_SESSION['role'] !== 'midwife') {
    header("Location: login.php");
    exit();
}

$db = new Database();
$midwife_id = $_SESSION['medical_staff_id'];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_meal':
                $query = "INSERT INTO meals (week, complication, title, description, recipe, created_by) 
                         VALUES (?, ?, ?, ?, ?, ?)";
                $params = [
                    $_POST['week'],
                    $_POST['complication'],
                    $_POST['title'],
                    $_POST['description'],
                    $_POST['recipe'],
                    $midwife_id
                ];
                if ($db->save($query, $params)) {
                    $_SESSION['success'] = "Meal added successfully!";
                } else {
                    $_SESSION['error'] = "Failed to add meal.";
                }
                break;

            case 'edit_meal':
                $query = "UPDATE meals SET week = ?, complication = ?, title = ?, description = ?, recipe = ? 
                         WHERE id = ? AND created_by = ?";
                $params = [
                    $_POST['week'],
                    $_POST['complication'],
                    $_POST['title'],
                    $_POST['description'],
                    $_POST['recipe'],
                    $_POST['meal_id'],
                    $midwife_id
                ];
                
                // Debug information
                error_log("Update meal query: " . $query);
                error_log("Update meal params: " . print_r($params, true));
                
                if ($db->save($query, $params)) {
                    $_SESSION['success'] = "Meal updated successfully!";
                } else {
                    $_SESSION['error'] = "Failed to update meal. Please try again.";
                    error_log("Failed to update meal. Query: " . $query . " Params: " . print_r($params, true));
                }
                break;

            case 'delete_meal':
                $query = "DELETE FROM meals WHERE id = ? AND created_by = ?";
                if ($db->save($query, [$_POST['meal_id'], $midwife_id])) {
                    $_SESSION['success'] = "Meal deleted successfully!";
                } else {
                    $_SESSION['error'] = "Failed to delete meal.";
                }
                break;

            case 'add_exercise':
                $query = "INSERT INTO exercises (week, complication, title, description, image, video_url, created_by) 
                         VALUES (?, ?, ?, ?, ?, ?, ?)";
                $params = [
                    $_POST['week'],
                    $_POST['complication'],
                    $_POST['title'],
                    $_POST['description'],
                    $_POST['image'] ?? null,
                    $_POST['video_url'] ?? null,
                    $midwife_id
                ];
                if ($db->save($query, $params)) {
                    $_SESSION['success'] = "Exercise added successfully!";
                } else {
                    $_SESSION['error'] = "Failed to add exercise.";
                }
                break;

            case 'edit_exercise':
                $query = "UPDATE exercises SET week = ?, complication = ?, title = ?, description = ?, 
                         image = ?, video_url = ? WHERE id = ? AND created_by = ?";
                $params = [
                    $_POST['week'],
                    $_POST['complication'],
                    $_POST['title'],
                    $_POST['description'],
                    $_POST['image'] ?? null,
                    $_POST['video_url'] ?? null,
                    $_POST['exercise_id'],
                    $midwife_id
                ];
                if ($db->save($query, $params)) {
                    $_SESSION['success'] = "Exercise updated successfully!";
                } else {
                    $_SESSION['error'] = "Failed to update exercise.";
                }
                break;

            case 'delete_exercise':
                $query = "DELETE FROM exercises WHERE id = ? AND created_by = ?";
                if ($db->save($query, [$_POST['exercise_id'], $midwife_id])) {
                    $_SESSION['success'] = "Exercise deleted successfully!";
                } else {
                    $_SESSION['error'] = "Failed to delete exercise.";
                }
                break;
        }
        header("Location: midwife_content.php");
        exit();
    }
}

// Get existing content
$meals = $db->read("SELECT * FROM meals WHERE created_by = ? ORDER BY week, complication", [$midwife_id]);
$exercises = $db->read("SELECT * FROM exercises WHERE created_by = ? ORDER BY week, complication", [$midwife_id]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Midwife Content Management</title>
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
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ffcdd2;
            border-radius: 6px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="number"]:focus,
        textarea:focus,
        select:focus {
            border-color: #e91e63;
            outline: none;
        }
        .btn {
            background-color: #e91e63;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #c2185b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ffcdd2;
        }
        th {
            background-color: #fff5f7;
            color: #e91e63;
            font-weight: bold;
        }
        tr:hover {
            background-color: #fff5f7;
        }
        h1, h2 {
            color: #e91e63;
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
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .edit-btn, .delete-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .edit-btn {
            background-color: #4CAF50;
            color: white;
        }
        .edit-btn:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
        }
        .delete-btn:hover {
            background-color: #da190b;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal.show {
            display: flex;
        }
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }
        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="midwife_dashboard.php" class="back-link">← Back to Dashboard</a>
        <h1>Content Management</h1>
        
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

        <!-- Add Meal Form -->
        <h2>Add New Meal</h2>
        <form method="POST">
            <input type="hidden" name="action" value="add_meal">
            <div class="form-group">
                <label for="meal_week">Week:</label>
                <input type="number" id="meal_week" name="week" min="1" max="40" required>
            </div>
            <div class="form-group">
                <label for="meal_complication">Condition:</label>
                <select id="meal_complication" name="complication" required>
                    <option value="normal">Normal Pregnancy</option>
                    <option value="morning_sickness">Morning Sickness</option>
                    <option value="high_blood_pressure">High Blood Pressure</option>
                    <option value="gestational_diabetes">Gestational Diabetes</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="meal_title">Title:</label>
                <input type="text" id="meal_title" name="title" required>
            </div>
            <div class="form-group">
                <label for="meal_description">Description:</label>
                <textarea id="meal_description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="meal_recipe">Recipe:</label>
                <textarea id="meal_recipe" name="recipe" required></textarea>
            </div>
            <button type="submit" class="btn">Add Meal</button>
        </form>

        <!-- Add Exercise Form -->
        <h2>Add New Exercise</h2>
        <form method="POST">
            <input type="hidden" name="action" value="add_exercise">
            <div class="form-group">
                <label for="exercise_week">Week:</label>
                <input type="number" id="exercise_week" name="week" min="1" max="40" required>
            </div>
            <div class="form-group">
                <label for="exercise_complication">Condition:</label>
                <select id="exercise_complication" name="complication" required>
                    <option value="normal">Normal Pregnancy</option>
                    <option value="morning_sickness">Morning Sickness</option>
                    <option value="high_blood_pressure">High Blood Pressure</option>
                    <option value="gestational_diabetes">Gestational Diabetes</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="exercise_title">Title:</label>
                <input type="text" id="exercise_title" name="title" required>
            </div>
            <div class="form-group">
                <label for="exercise_description">Description:</label>
                <textarea id="exercise_description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="exercise_image">Image URL (optional):</label>
                <input type="text" id="exercise_image" name="image">
            </div>
            <div class="form-group">
                <label for="exercise_video">Video URL (optional):</label>
                <input type="text" id="exercise_video" name="video_url">
            </div>
            <button type="submit" class="btn">Add Exercise</button>
        </form>

        <!-- Display Existing Content -->
        <h2>Existing Meals</h2>
        <table>
            <thead>
                <tr>
                    <th>Week</th>
                    <th>Condition</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Recipe</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($meals as $meal): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($meal['week']); ?></td>
                        <td><?php echo htmlspecialchars($meal['complication']); ?></td>
                        <td><?php echo htmlspecialchars($meal['title']); ?></td>
                        <td><?php echo htmlspecialchars($meal['description']); ?></td>
                        <td><?php echo htmlspecialchars($meal['recipe']); ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="edit-btn" onclick="editMeal(<?php echo $meal['id']; ?>)">Edit</button>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="delete_meal">
                                    <input type="hidden" name="meal_id" value="<?php echo $meal['id']; ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Existing Exercises</h2>
        <table>
            <thead>
                <tr>
                    <th>Week</th>
                    <th>Condition</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Video</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exercises as $exercise): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($exercise['week']); ?></td>
                        <td><?php echo htmlspecialchars($exercise['complication']); ?></td>
                        <td><?php echo htmlspecialchars($exercise['title']); ?></td>
                        <td><?php echo htmlspecialchars($exercise['description']); ?></td>
                        <td><?php echo htmlspecialchars($exercise['image'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($exercise['video_url'] ?? 'N/A'); ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="edit-btn" onclick="editExercise(<?php echo $exercise['id']; ?>)">Edit</button>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="delete_exercise">
                                    <input type="hidden" name="exercise_id" value="<?php echo $exercise['id']; ?>">
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Meal Modal -->
    <div id="editMealModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editMealModal')">&times;</span>
            <h2>Edit Meal</h2>
            <form method="POST" id="editMealForm">
                <input type="hidden" name="action" value="edit_meal">
                <input type="hidden" name="meal_id" id="edit_meal_id">
                <div class="form-group">
                    <label for="edit_meal_week">Week:</label>
                    <input type="number" id="edit_meal_week" name="week" min="1" max="40" required>
                </div>
                <div class="form-group">
                    <label for="edit_meal_complication">Condition:</label>
                    <select id="edit_meal_complication" name="complication" required>
                        <option value="normal">Normal Pregnancy</option>
                        <option value="morning_sickness">Morning Sickness</option>
                        <option value="high_blood_pressure">High Blood Pressure</option>
                        <option value="gestational_diabetes">Gestational Diabetes</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_meal_title">Title:</label>
                    <input type="text" id="edit_meal_title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="edit_meal_description">Description:</label>
                    <textarea id="edit_meal_description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="edit_meal_recipe">Recipe:</label>
                    <textarea id="edit_meal_recipe" name="recipe" required></textarea>
                </div>
                <button type="submit" class="btn">Update Meal</button>
            </form>
        </div>
    </div>

    <!-- Edit Exercise Modal -->
    <div id="editExerciseModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editExerciseModal')">&times;</span>
            <h2>Edit Exercise</h2>
            <form method="POST" id="editExerciseForm">
                <input type="hidden" name="action" value="edit_exercise">
                <input type="hidden" name="exercise_id" id="edit_exercise_id">
                <div class="form-group">
                    <label for="edit_exercise_week">Week:</label>
                    <input type="number" id="edit_exercise_week" name="week" min="1" max="40" required>
                </div>
                <div class="form-group">
                    <label for="edit_exercise_complication">Condition:</label>
                    <select id="edit_exercise_complication" name="complication" required>
                        <option value="normal">Normal Pregnancy</option>
                        <option value="morning_sickness">Morning Sickness</option>
                        <option value="high_blood_pressure">High Blood Pressure</option>
                        <option value="gestational_diabetes">Gestational Diabetes</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_exercise_title">Title:</label>
                    <input type="text" id="edit_exercise_title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="edit_exercise_description">Description:</label>
                    <textarea id="edit_exercise_description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="edit_exercise_image">Image URL (optional):</label>
                    <input type="text" id="edit_exercise_image" name="image">
                </div>
                <div class="form-group">
                    <label for="edit_exercise_video">Video URL (optional):</label>
                    <input type="text" id="edit_exercise_video" name="video_url">
                </div>
                <button type="submit" class="btn">Update Exercise</button>
            </form>
        </div>
    </div>

    <script>
        function editMeal(id) {
            // Fetch meal data and populate the form
            fetch(`get_meal.php?id=${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(meal => {
                    if (meal.error) {
                        alert(meal.error);
                        return;
                    }
                    document.getElementById('edit_meal_id').value = meal.id;
                    document.getElementById('edit_meal_week').value = meal.week;
                    document.getElementById('edit_meal_complication').value = meal.complication;
                    document.getElementById('edit_meal_title').value = meal.title;
                    document.getElementById('edit_meal_description').value = meal.description;
                    document.getElementById('edit_meal_recipe').value = meal.recipe;
                    document.getElementById('editMealModal').classList.add('show');
                })
                .catch(error => {
                    console.error('Error fetching meal:', error);
                    alert('Failed to load meal data. Please try again.');
                });
        }

        function editExercise(id) {
            // Fetch exercise data and populate the form
            fetch(`get_exercise.php?id=${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(exercise => {
                    if (exercise.error) {
                        alert(exercise.error);
                        return;
                    }
                    document.getElementById('edit_exercise_id').value = exercise.id;
                    document.getElementById('edit_exercise_week').value = exercise.week;
                    document.getElementById('edit_exercise_complication').value = exercise.complication;
                    document.getElementById('edit_exercise_title').value = exercise.title;
                    document.getElementById('edit_exercise_description').value = exercise.description;
                    document.getElementById('edit_exercise_image').value = exercise.image || '';
                    document.getElementById('edit_exercise_video').value = exercise.video_url || '';
                    document.getElementById('editExerciseModal').classList.add('show');
                })
                .catch(error => {
                    console.error('Error fetching exercise:', error);
                    alert('Failed to load exercise data. Please try again.');
                });
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.classList.remove('show');
            }
        }
    </script>
</body>
</html> 