<?php

session_start();
if(!isset($_SESSION['Login']) || !$_SESSION['Login']){
    header('Location: zaloguj.php');
    exit();
}
require_once 'db.php';
$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die('Error: ' . $conn->connect_error);
} else{

    $id_osoby = $_POST['id_osoby'];
    $Czego_uczy = $_POST['Czego_uczy']?: NULL;
    $sql = "UPDATE `users` SET Czego_uczy = CASE
            WHEN '$Czego_uczy' = '' THEN NULL
            ELSE '$Czego_uczy'
        END
        WHERE id = '$id_osoby'";
    $result = $conn->query($sql);
    if (!$result) {
        die("Error: ". $result);
    }else{
        header("Location: change_roles.php?page={$_GET['page']}&search={$_GET['search']}");
    }
}
