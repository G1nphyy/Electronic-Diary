<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $is_okay = true;
    session_start();
    if (isset($_POST['password'])) {
        $Haslo = $_POST['password'];
        if ($Haslo == '') {
            $is_okay = false;
        } elseif (strlen($Haslo) < 8) {
            $is_okay = false;
        } elseif (str_contains($Haslo, " ")) {
            $is_okay = false;
        }
    }else {
        $is_okay = false;
    }
    if (isset($_POST['password_repeat'])) {
            $haslo1 = $_POST['password_repeat'];
            if ($haslo1 !== $Haslo) {
                $is_okay = false;
            }
    } else{
            $is_okay = false;
    }

    if ($is_okay) {
        $hashed_password = password_hash($Haslo, PASSWORD_DEFAULT);
        $id = $_SESSION['user_id'];
        require_once("db.php");
        $conn = new mysqli($server_name, $user_name, $password, $database);
        $result = $conn -> query("UPDATE users SET Haslo = '$hashed_password' WHERE id = '$id' ");
        if($result) {
            $_SESSION['Haslo_user'] = $hashed_password;
            
        }
        header('Location: Info.php');
        exit();
    }
}else{
    header('Location: Info.php');
    exit('Invalid request');
}