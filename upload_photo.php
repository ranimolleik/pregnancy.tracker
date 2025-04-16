<?php
// Only start session if it is not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the PhotoAlbum class
require_once("classes/PhotoAlbum.php");

// Check if mother_id is set in the session
if (!isset($_SESSION['mother_id'])) {
    header("Location: dashboard.php"); // Redirect to login page if not logged in
    exit();
}

$mother_id = $_SESSION['mother_id'];
$photoAlbum = new PhotoAlbum();
$photos = $photoAlbum->getPhotos($mother_id);

// Handle photo deletion if photo_id is set in the POST request
if (isset($_POST['photo_id'])) {
    $photo_id = $_POST['photo_id']; // Get the photo_id from the POST request

    // Get the photo details to find the file path
    $photo = $photoAlbum->getPhotoById($photo_id, $mother_id);

    if ($photo) {
        // Delete the photo from the database
        if ($photoAlbum->deletePhoto($photo_id, $mother_id)) {
            // Delete the file from the server
            $file_path = $photo['image'];
            if (file_exists($file_path)) {
                unlink($file_path); // Delete the file
            }
            $_SESSION['success'] = "Photo deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete photo from the database.";
        }
    } else {
        $_SESSION['error'] = "Photo not found.";
    }

    // Redirect back to the photo album page after deletion
    header("Location: photo_album.php");
    exit();
}

// Handle file upload if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['photo'])) {
    $image_name = $_FILES['photo']['name'];
    $image_size = $_FILES['photo']['size'];
    $tmp_name = $_FILES['photo']['tmp_name'];
    $error = $_FILES['photo']['error'];

    if ($error === 0) {
        if ($image_size > 5000000) {
            $em = "Sorry, your file is too large.";
            header("Location: photo_album.php?error=$em");
            exit();
        } else {
            $img_ex = pathinfo($image_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);

            $allowed_exs = array('jpg', 'jpeg', 'png', 'gif');

            if (in_array($img_ex_lc, $allowed_exs)) {
                $new_img_name = uniqid("IMG_", true) . "." . $img_ex_lc;
                $image_upload_path = 'uploads/' . $new_img_name;

                // Ensure the directory exists
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }

                // Move the uploaded file to the target directory
                if (move_uploaded_file($tmp_name, $image_upload_path)) {
                    // Save photo info to the database
                    if ($photoAlbum->uploadPhoto($mother_id, $image_upload_path)) {
                        // Redirect with success message
                        header("Location: photo_album.php?success=The file has been uploaded.");
                    } else {
                        $em = "Failed to save photo information in the database.";
                        header("Location: photo_album.php?error=$em");
                    }
                } else {
                    $em = "Failed to move uploaded file.";
                    header("Location: photo_album.php?error=$em");
                }
            } else {
                $em = "You can't upload files of this type.";
                header("Location: photo_album.php?error=$em");
            }
        }
    } else {
        $em = "Unknown error occurred!";
        header("Location: photo_album.php?error=$em");
    }
    exit();
}
?>