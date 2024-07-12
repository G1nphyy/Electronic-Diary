<?php
session_start();
if ($_SESSION['Rola_user'] !== 'Admin') {
    header('Location: zaloguj.php');
    exit();
}

$after = $_POST['After'];
$przedmiot = $_POST['Name_of_subject'];
require_once 'db.php';
$conn = new mysqli($server_name, $user_name, $password, $database);
if ($conn->connect_error) {
    die('ERROR: '. $conn->connect_error);
} else {
    $checkColumn = "SHOW COLUMNS FROM `users_oceny` LIKE '$przedmiot'";
    $checkColumnResult = $conn->query($checkColumn);
    if ($checkColumnResult->num_rows == 0) {
        $sql = "ALTER TABLE `users_oceny` ADD `$przedmiot` TEXT NULL AFTER `$after`";
        $result = $conn->query($sql);
        if ($result) {
            header("Location: change_roles.php?page={$_GET['page']}&search={$_GET['search']}");
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        $_SESSION['alert_column'] = "Przedmiot już istnieje."; 
        header("Location: change_roles.php?page={$_GET['page']}&search={$_GET['search']}");
    }

    $conn->close();
}
