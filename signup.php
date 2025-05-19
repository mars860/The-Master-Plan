<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "mydb";

$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up - The Master Plan</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles/general.css">
  <link rel="stylesheet" href="styles/signup.css">
</head>
<style>
  body {
    background: url("media/logsignpics/bstickers.png") no-repeat center center fixed;
    background-size: cover;
    font-family: 'Montserrat', sans-serif;
    background-color: #5BFFE6;
  }
</style>
<body>
  <div class="signup-container">
    <div class="signup-box">
      <img src="media/logsignpics/feather.png" alt="Logo" class="signup-logo" />
      <h1 class="signup-title">Create an<br />Account</h1>

      <form class="signup-form" action="#" method="post">
        <input type="text" name="username" placeholder="Username" class="input-field" required />
        <input type="email" name="email" placeholder="Email" class="input-field" required />
        <input type="password" name="password" placeholder="Password" class="input-field" required />
        <button type="submit" class="signup-btn">Sign up</button>
      </form>

      <?php
      if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $conn->real_escape_string($_POST['username']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check if username or email exists
        $check_sql = "SELECT username, email FROM tblusers WHERE username = '$username' OR email = '$email'";
        $check_result = $conn->query($check_sql);

        if ($check_result && $check_result->num_rows > 0) {
          $errors = [];
          while ($row = $check_result->fetch_assoc()) {
            if ($row['username'] === $username) $errors[] = "Username already exists";
            if ($row['email'] === $email) $errors[] = "Email already exists";
          }
          echo "<p style='color: red;'>" . implode(", ", $errors) . ". Try another.</p>";
        } else {
          // Insert new user
          $insert_sql = "INSERT INTO tblusers (`username`, `email`, `password`) VALUES ('$username', '$email', '$password')";
          $insert_result = $conn->query($insert_sql);

          if ($insert_result && $conn->affected_rows === 1) {
            echo "<p style='color: green;'>Signup successful! Welcome, $username.</p>";
          } else {
            echo "<p style='color: red;'>Problem saving user's information. Please try again.</p>";
          }
        }
      }
      ?>

      <p class="login-text">
        Already have an account?
        <a id="login-link" href="login.php" class="login-btn">Log in</a>
      </p>
    </div>
  </div>
</body>
</html>
