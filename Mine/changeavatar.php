<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $path = $_POST['icon'];
    $conn = new mysqli($server_name, $user_name, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $user_id = $_SESSION['user_id'];
    $sql = "UPDATE users SET icon = '$path' WHERE id = '$user_id'";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['Icon_user'] = $path;
        header("Location: Info.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $conn->close();
} else {
    header('Location: index.php');
    exit();
}