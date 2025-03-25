<?php
require_once 'db.php';
session_start();
$conn = new mysqli($server_name, $user_name, $password, $database);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['Id_szkoly'];

    if ($action === 'save') {
        $nazwa_szkoly = $_POST['nazwa_szkoly'];
        $adres_szkoly = $_POST['adres_szkoly'];
        $dyrektor_szkoly = $_POST['dyrektor_szkoly'];
        $zastepca_dyrektora_szkoly = $_POST['zastepca_dyrektora_szkoly'];
        $kod_szkoly = $_POST['kod_szkoly'];
        $pedagog = $_POST['pedagog'];
        $psyhiatra = $_POST['psyhiatra'];
        $lekarz = $_POST['lekarz'];
        $data_dolaczenia = $_POST['data_dolaczenia'];
        $typ_szkoly = $_POST['typ_szkoly'];
        $status_public_private = $_POST['status_public_private'];
        $internat = $_POST['internat'];

        $sql = "UPDATE schools SET 
                nazwa_szkoly = ?, adres_szkoly = ?, dyrektor_szkoly = ?, zastepca_dyrektora_szkoly = ?, 
                kod_szkoly = ?, pedagog = ?, psyhiatra = ?, lekarz = ?, 
                data_dolaczenia = ?, typ_szkoly = ?, status_public_private = ?, internat = ? 
                WHERE Id_szkoly = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssssssssi', $nazwa_szkoly, $adres_szkoly, $dyrektor_szkoly, $zastepca_dyrektora_szkoly,
                          $kod_szkoly, $pedagog, $psyhiatra, $lekarz, $data_dolaczenia, $typ_szkoly, $status_public_private, $internat, $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Rekord został zaktualizowany.";
        } else {
            $_SESSION['message'] = "Błąd podczas aktualizacji: {$conn->error}";
        }

    } elseif ($action === 'delete') {
        // Delete the record
        $sql = "DELETE FROM schools WHERE Id_szkoly = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Rekord został usunięty.";
        } else {
            $_SESSION['message'] = "Błąd podczas usuwania: {$conn->error}";
        }
    }

    header("Location: Admin_all_schools.php?". ((isset($_GET['page']) ? "page=" . $_GET['page'] : "") . (isset($_GET['search']) ? (isset($_GET['page']) ? "&" : "") . "search=" . urlencode($_GET['search']) : "")));
    exit();
}
