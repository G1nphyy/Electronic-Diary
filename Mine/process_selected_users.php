<?php
session_start();
if (!$_SESSION['Login'] || $_SESSION['Rola_user'] == 'Uczen' ) {
    header('Location: zaloguj.php');
    exit();
}

require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$selectedIds = $data['combinedIds'];
$czy_new_role = $data['czy_new_role'];
$new_role = $data['new_role'];
$czy_new_grade = $data['czy_new_grade'];
$subject_grade = $data['subject_grade'];
$subject_waga = $data['subject_waga'];
$subject_opis = $data['subject_opis'];
$subject = $data['subject'];
$czy_new_uczy = $data['czy_new_uczy'];
$Czego_uczy = $data['Czego_uczy'];
$czy_new_class = $data['czy_new_class'];
$Klasa = $data['Klasa'];
$_SESSION['data'] = $data;

header('Content-Type: application/json');

if (!empty($selectedIds)) {
    $conn = new mysqli($server_name, $user_name, $password, $database);

    if ($conn->connect_errno) {
        echo json_encode(['success' => false, 'message' => $conn->connect_error]);
        exit();
    }

    $conn->begin_transaction();
    try {
        if ($czy_new_role && !empty($new_role)) {
            foreach ($selectedIds as $userId) {
                $stmt = $conn->prepare("UPDATE users SET Rola = ? WHERE id = ?");
                $stmt->bind_param("si", $new_role, $userId);
                $stmt->execute();
                $stmt->close();
            }
        }

        if ($czy_new_grade && !empty($subject_grade) && !empty($subject_waga) && !empty($subject)) {
            foreach ($selectedIds as $userId) {
                $stmt = $conn->prepare("SELECT $subject FROM users_oceny WHERE id_ucznia = ?");
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $oceny = explode(',', $row[$subject]);
                    $oceny[] = $subject_grade . ":" . $subject_waga. "-".$subject_opis;
                    $oceny_str = implode(',', array_filter($oceny));
                    $update_stmt = $conn->prepare("UPDATE users_oceny SET $subject = ? WHERE id_ucznia = ?");
                    $update_stmt->bind_param("si", $oceny_str, $userId);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
                $stmt->close();
            }
        }

        if ($czy_new_uczy && !empty($Czego_uczy)) {
            foreach ($selectedIds as $userId) {
                $stmt = $conn->prepare("UPDATE users SET Czego_uczy = ? WHERE id = ?");
                $stmt->bind_param("si", $Czego_uczy, $userId);
                $stmt->execute();
                $stmt->close();
            }
        }

        if ($czy_new_class && !empty($Klasa)) {
            foreach ($selectedIds as $userId) {
                $stmt = $conn->prepare("UPDATE users SET Klasa = ? WHERE id = ?");
                $stmt->bind_param("si", $Klasa, $userId);
                $stmt->execute();
                $stmt->close();
            }
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Pomyślnie dokonano zmian.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()]);
    }

    $conn->close();
    exit();
} else {
    echo json_encode(['success' => false, 'message' => 'Nikt nie jest zaznaczony.']);
    header("Location: change_roles.php?page={$_GET['page']}&search={$_GET['search']}"); 
}
