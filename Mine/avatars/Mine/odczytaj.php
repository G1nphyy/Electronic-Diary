<?php
session_start();
require_once 'db.php';

$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$data = json_decode(file_get_contents('php://input'), true);


if (!isset($data['id'])) {
    die("ID not provided");
}

$id = $conn->real_escape_string($data['id']);
$user_id = $_SESSION['user_id'];

$sql = "UPDATE wiadomości SET odczytane = 1 WHERE id = '$id' and id_do = '$user_id'";
$conn->query($sql);
$conn->close();