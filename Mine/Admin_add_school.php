<?php

include 'db.php';

// Create connection
$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

}
session_start();
if (!isset($_SESSION['Login']) && !$_SESSION['Login'] && $_SESSION['Rola_user'] != 'Admin_d') {
    header('Location: zaloguj.php');
    exit();
}


$nazwa_szkoly = $_POST['nazwa_szkoly'];
$adres_szkoly = $_POST['adres_szkoly'];
$dyrektor_szkoly = $_POST['dyrektor_szkoly'];
$zastepca_dyrektora_szkoly = $_POST['zastepca_dyrektora_szkoly'];
$pedagog = $_POST['pedagog'];
$psyhiatra = $_POST['psyhiatra'];
$lekarz = $_POST['lekarz'];
$data_dolaczenia = $_POST['data_dolaczenia'];
$typ_szkoly = $_POST['typ_szkoly'];
$status_public_private = $_POST['status_public_private'];
$internat = $_POST['internat'];
$kod_szkoly = $_POST['kod_szkoly'];

$sql = "INSERT INTO schools (nazwa_szkoly, adres_szkoly, dyrektor_szkoly, zastepca_dyrektora_szkoly, pedagog, psyhiatra, lekarz, data_dolaczenia, typ_szkoly, status_public_private, internat, kod_szkoly)
        VALUES ('$nazwa_szkoly', '$adres_szkoly', '$dyrektor_szkoly', '$zastepca_dyrektora_szkoly', '$pedagog', '$psyhiatra', '$lekarz', '$data_dolaczenia', '$typ_szkoly', '$status_public_private', '$internat', '$kod_szkoly')";

if ($conn->query($sql) === TRUE) {
    header('Location: Admin_new_school.php');
    exit();
} else {
    echo "Błąd: " . $sql . "<br>" . $conn->error;
}

$conn->close();