<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Pregnancy Tracker</title>
    <link rel="stylesheet" href="styles.css">
    <style>
           body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            text-align: center;
        }

        header {
            background-color: #ff69b4;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-left: 20px;
        }

        .nav-links {
            list-style: none;
            display: flex;
            margin-right: 20px;
        }

        .nav-links li {
            margin-left: 20px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #ff85c0;
        }

        .about-us {
            background-color: #f0f8ff;
            padding: 60px 20px;
            border-bottom: 5px solid #ff69b4;
            text-align: center;
        }

        .about-us h1 {
            font-size: 36px;
            color: #ff69b4;
            text-align: center;
        }

        .about-us p {
            font-size: 15dpx;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
        }

        .user-options {
            margin-top: 20px;
        }

        .button {
            background-color: #ff69b4;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 8px;
            margin: 10px;
            display: inline-block;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #ff85c0;
        }

        .slider {
            max-width: 80%;
            margin: 40px auto;
            overflow: hidden;
            text-align: center;
        }

        .slides {
            display: flex;
            transition: transform 0.5s ease;
        }

        .slide {
            min-width: 33.33%; /* Adjusted to show 3 images at a time */
            box-sizing: border-box;
        }

        .slide img {
            width: 80%; /* Set the width of the images */
            height: auto; /* Maintain aspect ratio */
            border-radius: 10px;
            margin: 0 auto; /* Center the images */
            display: block; /* Ensure images are block elements */
        }

        footer {
            background-color: #ff69b4;
            color: white;
            text-align: center;
            padding: 20px 0;
            position: relative;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Pregnancy Tracker</div>
        <ul class="nav-links">
            <li><a href="index.php">Signup</a></li>
            <li><a href="login.php">Login</a></li>
        </ul>
    </header>

    <section class="about-us">
        <h1>Start Your Journey with Us</h1>
        Welcome to Pregnancy Tracker, your trusted companion during your pregnancy journey.
         Our mission is to provide essential support through tracking tools, expert advice, and a vibrant community.</p>
        <p>We offer personalized tracking features to help you monitor your health and your baby's development. 
            Our team of experienced midwives and doctors is available to provide professional guidance and answer your questions.</p>
        <div class="user-options">
            <a href="apply.php" class="button">Apply as a Midwife/Doctor</a>
            <a href="login.php" class="button">Login</a>
        </div>
    </section>

    <section class="slider">
        <div class="slides">
            <div class="slide"><img src="/images/download (4).jpg" alt="Baby Picture 1"></div>
            <div class="slide"><img src="/images/download.jpg" alt="Baby Picture 2"></div>
            <div class="slide"><img src="/images/𝙼𝚊𝚍𝚛𝚎𝚜.jpg" alt="Baby Picture 3"></div>
            
        </div>
    </section>

    <footer>
        <p>© 2025 Pregnancy Tracker. All rights reserved.</p>
    </footer>

   
</body>
</html>
