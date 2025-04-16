<?php
// Include mother session configuration at the very beginning
include("mother_session_config.php");
require_once("classes/PhotoAlbum.php"); // Include the PhotoAlbum class

// Check if mother_id is set in the session
if (!isset($_SESSION['mother_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

$mother_id = $_SESSION['mother_id']; // Get the actual mother ID from the session
include("upload_photo.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Photo Album - Pregnancy Tracker</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    /* Photo Upload Section */
    .photo-upload {
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .photo-upload h2 {
      color: #FF4F94;
      margin-bottom: 15px;
    }

    .photo-upload form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .photo-upload label {
      color: #333;
      font-weight: 500;
    }

    .photo-upload input[type="file"] {
      padding: 8px;
      border: 2px solid #FFB6C1;
      border-radius: 5px;
      background-color: #fff;
    }

    .photo-upload button {
      background-color: #FF4F94;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .photo-upload button:hover {
      background-color: #FF1A75;
    }

    /* Photo Gallery Section */
    .photo-gallery {
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .photo-gallery h2 {
      color: #FF4F94;
      margin-bottom: 15px;
    }

    #photo-gallery {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 20px;
    }

    .photo-item {
      position: relative;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
    }

    .photo-item:hover {
      transform: translateY(-5px);
    }

    #photo-gallery img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
    }

    /* Delete Button Styling */
    .delete-button {
      position: absolute;
      bottom: 10px;
      right: 10px;
      background-color: #FF4F94;
      color: white;
      border: none;
      border-radius: 5px;
      padding: 5px 10px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .delete-button:hover {
      background-color: #FF1A75;
    }

    /* Alert Messages */
    .alert {
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 5px;
      font-weight: 500;
    }

    .alert-success {
      background-color: #D4EDDA;
      color: #155724;
      border: 1px solid #C3E6CB;
    }

    .alert-error {
      background-color: #F8D7DA;
      color: #721C24;
      border: 1px solid #F5C6CB;
    }

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

    .header-actions a {
      color: white;
      text-decoration: none;
      margin-left: 15px;
      transition: color 0.3s;
    }

    .header-actions a:hover {
      color: #FFB6C1;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <aside class="sidebar">
      <h2>Pregnancy Tracker</h2>
      <nav>
        <a href="dashboard.php">Home</a>
        <a href="#">Meals</a>
        <a href="#">Exercises</a>
        <a href="photo_album.php" class="active">Photo Album</a>
        <a href="notes.php">Notes</a>
      </nav>
    </aside>

    <main class="main-content">
      <header>
        <h1>Photo Album</h1>
        <div class="header-actions">
          <a href="#" id="account-link">Account</a>
          <a href="#" id="logout-link">Logout</a>
        </div>
      </header>

      <!-- Display success or error messages -->
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
          <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
      <?php endif; ?>
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
          <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
        <p><?php echo $_GET['error']; ?> </p>
        <?php endif; ?>
      <!-- Photo Upload Section -->
      <section class="photo-upload">
        <h2>Upload a Photo</h2>
        <form id="upload-form" action="upload_photo.php" method="POST" enctype="multipart/form-data">
          <label for="photo">Select a Photo:</label>
          <input type="file" name="photo" id="photo" accept="image/*" required>
          <button type="submit">Upload</button>
        </form>
      </section>
      <section class="photo-gallery">
    <h2>Your Photos</h2>
    <div id="photo-gallery">
        <?php if (!empty($photos) && is_array($photos)): ?>
            <?php foreach ($photos as $photo): ?>
                <div class="photo-item">
                    <img src="<?php echo htmlspecialchars($photo['image']); ?>" alt="Uploaded Photo" title="<?php echo htmlspecialchars($photo['uploaded_at']); ?>">
                    <form action="upload_photo.php" method="POST" class="delete-form">
                        <input type="hidden" name="photo_id" value="<?php echo htmlspecialchars($photo['id']); ?>">
                        <button type="submit" class="delete-button">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No photos uploaded yet.</p>
        <?php endif; ?>
    </div>
</section>


  <script src="script.js"></script>
</body>
</html>