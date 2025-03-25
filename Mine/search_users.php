<?php
require_once 'db.php';
session_start();
$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$query = $_GET['query'];
$sql = "SELECT id, Imie, Nazwisko, `E-mail`, Rola FROM users WHERE Imie LIKE '%$query%' OR Nazwisko LIKE '%$query%' or `E-mail` LIKE '%$query%' ";
if($_SESSION['Rola_user'] != 'Admin_d' and $_SESSION['Rola_user'] != 'Admin' and $_SESSION['Szkola_user'] != 0) {
    $sql .= " AND nalezy_id_szkoly = '".$_SESSION['Szkola_user']."'";
}


$result = $conn->query($sql);

$users = array();
while($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
$conn->close();