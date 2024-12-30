<?php
session_start();

if (!isset($_SESSION['Login']) && !$_SESSION['Login'] && $_SESSION['Rola_user'] != 'Admin_d') {
    header('Location: zaloguj.php');
    exit();
}

require 'db.php';
$conn = new mysqli($server_name, $user_name, $password, $database);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userId = $_POST['user_id'] ?? null;
    $schoolId = $_POST['school_id'] ?? null;


    if ($userId && $schoolId) {
        try {
            $stmt = $conn->prepare(
                "UPDATE users SET nalezy_id_szkoly = ? WHERE id = ?"
            );
            $stmt->bind_param('ii', $schoolId, $userId);

            if ($stmt->execute()) {
                echo 'Szkoła została pomyślnie zaktualizowana.';
            } else {
                echo  'Wystąpił błąd podczas aktualizacji szkoły.';
            }

            $stmt->close();
        } catch (Exception $e) {

            echo 'Wystąpił błąd: ' . $e->getMessage();
        }
    } else {
        echo'Nieprawidłowe dane wejściowe.';
    }
    header('Location: change_roles.php');
    exit;
} else {
    header('Location: change_roles.php');
    exit;
}
