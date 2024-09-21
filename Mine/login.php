<?php
session_start();
require_once("db.php");


if ($_SESSION['Login']) {
    header('Location: welcome.php');
    exit();
}
$conn = @new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_errno != 0) {
    echo $conn->connect_errno . " ";
    echo '<br> <a href="zaloguj_rejstracja.php">Wróć do strony logowania</a>';
} else {
    $is_okay = true;
    if (isset($_POST['login_l'])) {
        $login = $_POST['login_l'];
        $_SESSION['checking_login_l'] = $login;
        if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['login_el'] = 'Błędna składnia e-mail';
            $is_okay = false;
        }
        if($login == ''){
            $_SESSION['login_el'] = 'Nie podano loginu';
            $is_okay = false;
        }
    }else{
        $is_okay = false;
    }
    if (isset($_POST['haslo_l'])) {
        $haslo = $_POST['haslo_l'];
        $_SESSION['checking_haslo_l'] = $haslo;
        if($haslo == ''){
            $_SESSION['haslo_el'] = 'Nie podano hasła';
            $is_okay = false;
        }
    } else {
        $is_okay = false;
    }

    if (!$is_okay) {
        header('Location: zaloguj_rejstracja.php');
        exit();
    }

    $login = htmlentities($login, ENT_QUOTES, 'UTF-8');
    $haslo = htmlentities($haslo, ENT_QUOTES, 'UTF-8');

    $sql = "SELECT * FROM users WHERE `E-mail` = '$login'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $wiersz = $result->fetch_assoc();
        if (password_verify($haslo,$wiersz['Haslo'])){
            $_SESSION['Imie_user'] = $wiersz['Imie'];
            $_SESSION['Nazwisko_user'] = $wiersz['Nazwisko'];
            $_SESSION['E-mail_user'] = $wiersz['E-mail'];
            $_SESSION['Haslo_user'] = $wiersz['Haslo'];
            $_SESSION['Rola_user'] = $wiersz['Rola'];
            $_SESSION['Czego_uczy_user'] = $wiersz['Czego_uczy'];
            $_SESSION['user_id'] = $wiersz['id'];
            $_SESSION['Klasa_user'] = $wiersz['Klasa'];
            $_SESSION['Icon_user'] = $wiersz['icon'];
            $_SESSION['Login'] = true;
            header('Location: welcome.php');
            exit();
        }else{
            $_SESSION['haslo_el'] = 'Błędne hasło';
            header('Location: zaloguj_rejstracja.php');
            exit();
        }
    } else {
        $_SESSION['alert_l'] = 'Nie odnaleziono urzytkownika w bazie danych';
        header('Location: zaloguj_rejstracja.php');
        exit();
    }
}