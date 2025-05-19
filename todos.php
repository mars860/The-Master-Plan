<?php
session_start();
include 'config.php'; 


if (!isset($_SESSION['userid'])) {
    exit("User not logged in.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    $userid = $_SESSION['userid'];
    $title = $conn->real_escape_string($_POST['title']);
    $date = $conn->real_escape_string($_POST['date']);
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($_GET['action'] == 1) {
       
        $sql = "INSERT INTO tbllist (userid, title, date, type, iscompleted) 
                VALUES ($userid, '$title', '$date', 2, 0)";
    } elseif ($_GET['action'] == 2 && $id > 0) {
     
        $sql = "UPDATE tbllist SET title = '$title', date = '$date' WHERE id = $id AND userid = $userid";
    } elseif ($_GET['action'] == 3 && $id > 0) {
    
        $sql = "DELETE FROM tbllist WHERE id = $id AND userid = $userid AND type = 2";
    } else {
        exit("Invalid action.");
    }

    if ($conn->query($sql) === TRUE) {
        header('Location: ./dashboard.php?page=todo');
        exit();
    } else {
        exit("Error: " . $conn->error);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['action']) && $_GET['action'] == 3) && (isset($_GET['id']) && $_GET['id'] > 0)) {
    $userid = $_SESSION['userid'];
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $sql = "DELETE FROM tbllist WHERE id = $id AND userid = $userid AND type = 2";
    if ($conn->query($sql) === TRUE) {
        header('Location: ./dashboard.php?page=todo');
        exit();
    } else {
        exit("Error: " . $conn->error);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['action']) && $_GET['action'] == 4) && (isset($_GET['id']) && $_GET['id'] > 0) && (isset($_GET['status']) && $_GET['status'] > 0)) {
    $userid = $_SESSION['userid'];
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $status = isset($_GET['status']) ? intval($_GET['status']) : 0;
    $sql = "UPDATE tbllist SET iscompleted = '$status' WHERE id = $id AND userid = $userid";
    if ($conn->query($sql) === TRUE) {
        header('Location: ./dashboard.php');
        exit();
    } else {
        exit("Error: " . $conn->error);
    }
}
?>
