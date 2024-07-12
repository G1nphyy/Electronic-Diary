<?php
session_start();
if (!$_SESSION['Login'] || $_SESSION['Rola_user'] == 'Uczen' ) {
    header('Location: zaloguj.php');
    exit();
}

$id_ucznia = $_POST['user_id'];
$ocena = $_POST['subject_grade'];
$przedmiot = $_POST['subject'];
$waga = $_POST['subject_waga'] !== "" ? $_POST['subject_waga'] : '1';
$opis = $_POST['opis'] ?? '';
require_once 'db.php';
$conn = new mysqli($server_name, $user_name, $password, $database);
if ($conn->connect_error) {
    die('ERROR: '. $conn->connect_error);
} else {
    $id_ucznia = $conn->real_escape_string($id_ucznia);
    $ocena = $conn->real_escape_string($ocena);
    $przedmiot = $conn->real_escape_string($przedmiot);

    $sql = "SELECT * FROM users_oceny WHERE id_ucznia = '$id_ucznia'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
     
        $oceny = $row[$przedmiot]; 
        $oceny = explode(',', $oceny); 
        $oceny[] = $ocena.":".$waga."-".$opis;
        if(empty($oceny[0])) {
            $oceny_str = implode('', $oceny);
        }else{
            $oceny_str = implode(',', $oceny);
        }
        
        $update_sql = "UPDATE users_oceny SET `$przedmiot` = '$oceny_str' WHERE id_ucznia = '$id_ucznia'";
        if ($conn->query($update_sql) === TRUE) {
            header("Location: change_roles.php?page={$_GET['page']}&search={$_GET['search']}");
            exit();
        } else {
            echo "Error updating grades: " . $conn->error;
        }
    } else {
        echo "No record found for this student.";
    }

    $conn->close();
    header("Location: change_roles.php?page={$_GET['page']}&search={$_GET['search']}");
}