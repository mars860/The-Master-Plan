<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "myDB"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



function connect() {
    return new mysqli("localhost", "root", "", "myDB");
}

