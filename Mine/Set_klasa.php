<?php
session_start();
if ($_SESSION['Rola_user'] !== 'Admin') {
    header('Location: Index.php');
    exit();
}

require_once 'db.php';
$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_errno != 0) {
    echo "Error: " . $conn->connect_error;
} else {
    $user_id = $_POST['id_user'];
    $klasa = $_POST['Klasa'];

    $sql = "UPDATE users SET Klasa = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $klasa, $user_id);

    if ($stmt->execute()) {
        header("Location: change_roles.php?page={$_GET['page']}&search={$_GET['search']}");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();