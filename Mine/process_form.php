<?php
session_start();
if ($_SESSION['Rola_user'] !== 'Admin' and $_SESSION['Rola_user'] !== 'Admin_d' ) {
    header('Location: zaloguj.php');
    exit();
}

require_once 'db.php';

$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die('Error connecting to database: '.$conn->connect_error);
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $klasa = isset($_POST['klasa']) ? $_POST['klasa'] : false;
    if($_SESSION['Rola_user'] == "Admin_d"){
        $szkola = isset($_POST['szkola_add']) ? $_POST['szkola_add'] : false;
    }else{
        $szkola = $_SESSION['Szkola_user'];
    }
    if (!$klasa or !$szkola) {
        $_SESSION['message'] = '<span style="color: red">Nie podano klasy lub szkoły</span>';
        header('Location: dodaj_plan_lekcji.php');
        exit();
    }
    $formDataJson = $_POST['formDataJson'];
    $formData = json_decode($formDataJson, true);

    $sql = "INSERT INTO `plany lekcji` (Klasa, Poniedzialek, Wtorek, Sroda, Czwartek, Piatek, nalezy_id_szkoly) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $poniedzialek = $formData['poniedzialek'] ? json_encode($formData['poniedzialek']) : null;
    $wtorek = $formData['wtorek'] ? json_encode($formData['wtorek']) : null;
    $sroda = $formData['sroda'] ? json_encode($formData['sroda']) : null;
    $czwartek = $formData['czwartek'] ? json_encode($formData['czwartek']) : null;
    $piatek = $formData['piatek'] ? json_encode($formData['piatek']) : null;
    $stmt->bind_param('ssssssi', $klasa, $poniedzialek, 
                                  $wtorek, 
                                  $sroda, 
                                  $czwartek, 
                                  $piatek,
                                  $szkola);

    if ($stmt->execute()) {
        
        $_SESSION['message'] = "Plan lekcji został dodany pomyślnie!";
        header('Location: dodaj_plan_lekcji.php');
    } else {
        $_SESSION['message'] = "Błąd: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();