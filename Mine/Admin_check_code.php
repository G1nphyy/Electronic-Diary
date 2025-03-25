<?php

require_once "db.php";
$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!isset($_SESSION['Login']) && !$_SESSION['Login'] && $_SESSION['Rola_user'] != 'Admin_d') {
    header('Location: zaloguj.php');
    exit();
}


if (isset($_POST['kod_szkoly'])) {
    $kod_szkoly = $_POST['kod_szkoly'];

    $sql = "SELECT * FROM schools WHERE kod_szkoly = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $kod_szkoly);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo 'exists';
    } else {
        echo 'available';
    }
    
    $stmt->close();
}

$conn->close();