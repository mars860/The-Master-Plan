<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "mydb";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST['username'], $_POST['email'], $_POST['message'])) {

    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($username) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO contact_messages (username, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $message);

        if ($stmt->execute()) {
            $success = "✅ Message sent successfully!";
        } else {
            $error = "❌ Message not sent. Please try again.";
        }

        $stmt->close();
    } else {
        $error = "❌ Please fill in all fields correctly.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us</title>
  <link rel="stylesheet" href="styles/general.css" />
  <link rel="stylesheet" href="styles/contactus.css" />
  <style>
    body {
      background: url("media/logsignpics/bstickers.png") no-repeat center center fixed;
      background-size: cover;
      font-family: 'Montserrat', sans-serif;
      background-color: #5BFFE6;
    }
  </style>
</head>
<body>
  
    <div class="contact-form-container">
        <div class="contact-form-box">
          <img src="media/logsignpics/feather.png" alt="Logo" class="contact-form-logo">
          <h2 class="contact-form-title">Contact Us</h2>

        <form action="contactus.php" method="POST">
            <input type="text" name="username" class="contact-input" placeholder="Username" required>
            <input type="email" name="email" class="contact-input" placeholder="Email" required>
            <textarea name="message" class="contact-input" placeholder="Message" rows="4" required></textarea>

                <div class="form-submit-container">
                  <button type="submit" class="contact-send-btn">Send</button>
                  <?php if (!empty($success)) echo "<span class='form-notification success'>$success</span>"; ?>
                  <?php if (!empty($error)) echo "<span class='form-notification error'>$error</span>"; ?>
                </div>
        </form>


        </div>
    </div>

    <script>
  setTimeout(() => {
    const notification = document.querySelector('.form-notification');
    if (notification) {
      notification.style.opacity = '0';
      notification.style.transition = 'opacity 0.5s ease';
    }
  }, 3000);
</script>

      
</body>
</html>
