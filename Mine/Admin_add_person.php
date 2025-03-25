<?php
session_start();
require_once('db.php');

$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}
if (!isset($_SESSION['Login']) && !$_SESSION['Login'] && $_SESSION['Rola_user'] != 'Admin_d') {
    header('Location: zaloguj.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $imie = $conn->real_escape_string($_POST['imie']);
    $nazwisko = $conn->real_escape_string($_POST['nazwisko']);
    $klasa = $conn->real_escape_string($_POST['klasa']);
    $email = $conn->real_escape_string($_POST['email']);
    $haslo = password_hash($conn->real_escape_string($_POST['haslo']), PASSWORD_DEFAULT); 
    $rola = $conn->real_escape_string($_POST['rola']);
    $czego_uczy = $conn->real_escape_string($_POST['czego_uczy']);
    $id_szkoly = $conn->real_escape_string($_POST['id_szkoly']);

    if (empty($imie) || empty($nazwisko) || empty($klasa) || empty($email) || empty($haslo) || empty($rola)) {
        die("Wszystkie pola są wymagane!");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Podano nieprawidłowy adres e-mail!");
    }

    $sql = "INSERT INTO users (Imie, Nazwisko, Klasa, `E-mail`, Haslo, Rola, Czego_uczy, nalezy_id_szkoly) 
            VALUES ('$imie', '$nazwisko', '$klasa', '$email', '$haslo', '$rola', '$czego_uczy', '$id_szkoly')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['isis'] = "Pomyślnie dodano nowego urzytkownika (".$imie. " ". $nazwisko.")";
        header("Location: Admin_new_person.php");
        exit();
    } else {
        echo "Błąd: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
?>
