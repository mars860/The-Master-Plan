<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>The Master Plan Dashboard</title>

  <!-- Add Font Awesome CDN for icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles/dashboard.css">



</head>

<body>

  <div class="sidebar">
    <div class="logo">
      <img src="media/dashboard/logo.png" alt="Logo">
      <h2>The Master Plan.</h2>
    </div>

    <div class="menu">
      <a href="dashboard.php" class="<?= $page === 'home' ? 'active' : '' ?>">
        <img src="media/dashboard/home.svg" class="menu-icon home-icon" alt="">
        <span>Dashboard</span>
      </a>
      <a href="dashboard.php?page=todo" class="<?= $page === 'todo' ? 'active' : '' ?>">
        <img src="media/dashboard/todos.png" class="menu-icon todo-icon" alt="">
        <span>To-Do List</span>
      </a>
      <a href="dashboard.php?page=note" class="<?= $page === 'note' ? 'active' : '' ?>">
        <img src="media/dashboard/notes.png" class="menu-icon note-icon" alt="">
        <span>Notes</span>
      </a>
    </div>


    <div class="user-section">
      <div class="user-info">
        <img src="media/dashboard/user.png" alt="User" />
        <p><?= htmlspecialchars($_SESSION['username']) ?></p>
      </div>
      <a class="logout" href="logout.php"><i class="fas fa-sign-out-alt"></i> Log out</a> <!-- Log out icon -->
    </div>

  </div>


  <main class="main-content">
    <?php
    if ($page === 'note') {
      include 'note.php';
    } elseif ($page === 'todo') {
      include 'todo.php';
    } else {
      include 'widgets.php';
    }
    ?>
  </main>

</body>
</html>
