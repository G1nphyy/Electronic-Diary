<?php
require_once 'db.php';
$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = $_GET['query'];
$sql = "SELECT id, Imie, Nazwisko, `E-mail` FROM users WHERE Imie LIKE '%$query%' OR Nazwisko LIKE '%$query%' or `E-mail` LIKE '%$query%'";
$result = $conn->query($sql);

$users = array();
while($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);
$conn->close();