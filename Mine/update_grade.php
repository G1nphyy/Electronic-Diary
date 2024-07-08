<?php
session_start();

if (!$_SESSION['Login'] || $_SESSION['Rola_user'] == 'Uczen' ) {
    header('Location: Index.php');
    exit();
}

require_once('db.php');

$conn = new mysqli($server_name, $user_name, $password, $database);


if ($conn->connect_error) {
    die('Error: ' . $conn->connect_error);
} else {
    $user_id = $_POST['user_id'];
    $subject = $_POST['subject'];
    $grade_id = $_POST['grade_id'];
    $edited_grade = $_POST['edited_grade'];
    $edited_waga = $_POST['waga'];
    $edited_opis = $_POST['opis'];
    if(!isset($user_id) and !isset($subject) and !isset($grade_id) and !isset($edited_grade) and !isset($edited_waga)){
        header("Location: change_roles.php?page={$_GET['page']}&search={$_GET['search']}");
    }


    $sql = "SELECT $subject FROM users_oceny WHERE id_ucznia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_grades_str = $row[$subject];

        $wszystkie_oceny = explode(",", $current_grades_str);
        $grades = [];
        foreach ($wszystkie_oceny as $mark) {
            list($ocena, $waga) = explode(":", $mark);
            list($waga, $opis) = explode("-", $waga);
            $grades[] = ['ocena' => $ocena, 'waga' => $waga, 'opis' => $opis];
        }
        $grades[$grade_id] = ['ocena' => $edited_grade, 'waga' => $edited_waga, 'opis' => $edited_opis];

        $updated_grades = [];
        foreach ($grades as $grade) {
            $updated_grades[] = $grade['ocena'] . ':' . $grade['waga']. '-' . $grade['opis'];
        }
        $updated_grades_str = implode(",", $updated_grades);

        $update_sql = "UPDATE users_oceny SET $subject = ? WHERE id_ucznia = ?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param('si', $updated_grades_str, $user_id);
        
        if ($stmt_update->execute()) {
            $_SESSION['message'] = 'Grade updated successfully.';
        } else {
            $_SESSION['error'] = 'Failed to update grade: ' . $stmt_update->error;
        }

        $stmt_update->close();
    } else {
        $_SESSION['error'] = 'Failed to fetch current grades.';
    }

    $stmt->close();
    $conn->close();

    header("Location: change_roles.php?page={$_GET['page']}&search={$_GET['search']}");
    exit();
}