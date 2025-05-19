<?php
session_start();

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$database = "mydb";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $database);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT id, username, password FROM tblusers WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
      $_SESSION['username'] = $user['username'];
      $_SESSION['userid'] = $user['id'];
      header("Location: dashboard.php");
      exit();
    } else {
      $error = "Invalid username or password.";
    }
  } else {
    $error = "Invalid username or password.";
  }

  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - The Master Plan</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles/general.css">
  <link rel="stylesheet" href="styles/login.css">
  <style>
    body {
      background: url("media/logsignpics/bstickers.png") no-repeat center center fixed;
      background-size: cover;
      font-family: 'Montserrat', sans-serif;
      background-color: #5BFFE6;
    }
  </style>

</head>
<body >
  <div class="login-container">
    <div class="login-box">
      <img src="media/logsignpics/feather.png" alt="Logo" class="login-logo" />
      <h1 class="login-title">Login to The<br />Master Plan</h1>

      <form class="login-form" action="login.php" method="post">
        <input type="text" name="username" placeholder="Username" class="input-field" required />
        <input type="password" name="password" placeholder="Password" class="input-field" required />
        <button type="submit" class="login-btn">Log in</button>
      </form>
      
      <?php if (isset($error)): ?>
      <p style="color: red; text-align: center; margin-top: 10px;"><?php echo $error; ?></p>
    <?php endif; ?>



      <p class="signup-text">
        Don't have an account?
        <a href="signup.php" class="signup-link">Sign up</a>
      </p>
    </div>
  </div>
</body>
</html>
