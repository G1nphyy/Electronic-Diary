<?php
session_start();
if ($_SESSION['Rola_user'] !== 'Admin') {
    header('Location: Index.php');
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
    if (!$klasa) {
        $_SESSION['message'] = '<span style="color: red">Nie podano klasy</span>';
        header('Location: dodaj_plan_lekcji.php');
        exit();
    }
    $formDataJson = $_POST['formDataJson'];
    $formData = json_decode($formDataJson, true);

    $sql = "INSERT INTO `plany lekcji` (Klasa, Poniedzialek, Wtorek, Sroda, Czwartek, Piatek) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $poniedzialek = $formData['poniedzialek'] ? json_encode($formData['poniedzialek']) : null;
    $wtorek = $formData['wtorek'] ? json_encode($formData['wtorek']) : null;
    $sroda = $formData['sroda'] ? json_encode($formData['sroda']) : null;
    $czwartek = $formData['czwartek'] ? json_encode($formData['czwartek']) : null;
    $piatek = $formData['piatek'] ? json_encode($formData['piatek']) : null;
    $stmt->bind_param('ssssss', $klasa, $poniedzialek, 
                                  $wtorek, 
                                  $sroda, 
                                  $czwartek, 
                                  $piatek);

    if ($stmt->execute()) {
        
        $_SESSION['message'] = "Plan lekcji został dodany pomyślnie!";
        header('Location: dodaj_plan_lekcji.php');
    } else {
        $_SESSION['message'] = "Błąd: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();