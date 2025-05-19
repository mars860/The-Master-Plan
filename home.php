<?php

require("./config.php");


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>The Master Plan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="./styles/general.css">
  <link rel="stylesheet" href="./styles/home.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

</head>
<body>
  
  <header class="header">
    <div class="logo-section">
      <img src="media/homepics/logo.png" alt="The Master Plan Logo" class="logo">
      <span class="brand-name">The Master Plan.</span>
    </div>
    <nav class="nav-links">
      <a href="home.php">Home</a>
      <a href="about.php">About</a>
      <a href="features.php">Features</a>
      <a href="contactus.php">Contact</a>

    </nav>
    <div class="auth-buttons">
      
      <a href="login.php" class="login-btn">Log in</a>
      <a href="signup.php" class="signup-btn">Sign up</a>
      
    </div>
  </header>

  <section class="hero-section">
    <div class="hero-text">
      <h1>The ultimate way to<br>plan, note, and thrive.</h1>
      <p>Your all-in-one digital planner to<br>organize tasks, take notes, and<br>stay on top of your studies & etc.</p>
      
      <a href="signup.php" class="get-started-btn">Get Started</a>

      
    </div>
    <div class="hero-image-container">
      <img class="student-background" src="media/homepics/studentB.png" alt="Hero student background">
      <img class="students" src="media/homepics/student.png" alt="Happy students" />
    </div>
  </section>

  <footer class="footer">
    <p>© 2025 The Master Plan. All rights reserved.</p>
  </footer>
</body>
</html>