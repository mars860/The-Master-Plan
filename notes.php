<?php
session_start();
include 'config.php'; 


if (!isset($_SESSION['userid'])) {
    exit("User not logged in.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    $userid = $_SESSION['userid'];
    $title = $conn->real_escape_string($_POST['title']);
    $about = $conn->real_escape_string($_POST['about']);
    $notes = $conn->real_escape_string($_POST['notes']);
    $id = isset($_GET['id']) ? intval(value: $_GET['id']) : 0;

    if ($_GET['action'] == 1) {
        $sql = "INSERT INTO tbllist (userid, title, about, notes, type) VALUES ($userid, '$title', '$about', '$notes', 1)";
    } elseif ($_GET['action'] == 2 && $id > 0) {
        $sql = "UPDATE tbllist SET title = '$title', about = '$about', notes = '$notes' WHERE id = $id and userid = $userid";
    } elseif ($_GET['action'] == 3 && $id > 0) {
        $sql = "DELETE FROM tbllist WHERE id = $id AND userid = $userid and type = 1";
    } else {
        exit("Invalid action.");
    }

    if ($conn->query($sql) === TRUE) {
        header('Location: ./dashboard.php?page=note');
        exit();
    } else {
        exit("Error: " . $conn->error);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET' && (isset($_GET['action']) && $_GET['action'] == 3)&& (isset($_GET['id']) && $_GET['id'] > 0)) {
    $userid = $_SESSION['userid'];
    $id = isset($_GET['id']) ? intval(value: $_GET['id']) : 0;
    $sql = "DELETE FROM tbllist WHERE id = $id AND userid = $userid and type = 1";
    if ($conn->query($sql) === TRUE) {
        header('Location: ./dashboard.php?page=note');
        exit();
    } else {
        exit("Error: " . $conn->error);
    }
}
?>
