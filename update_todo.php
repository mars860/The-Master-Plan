<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $isCompleted = isset($_POST['iscompleted']) ? intval($_POST['iscompleted']) : 0;
    $userId = $_SESSION['userid'];



    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE tbllist SET iscompleted = ? WHERE id = ? AND userid = ?");
        $stmt->bind_param("iii", $isCompleted, $id, $userId);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "DB Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid ID";
    }
}
?>
