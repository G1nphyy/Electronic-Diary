<?php
session_start();
require_once 'db.php';
$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$recipient = $_POST['recipient'];
$title = $_POST['messageTitle'];
$content = $_POST['messageContent'];
$sender_id = $_SESSION['user_id'];

$sql = "SELECT id FROM users WHERE CONCAT(Imie, ' ', Nazwisko, ' [', `E-mail`, '] ') = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $recipient);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $recipient_id = $row['id'];

    $sql = "INSERT INTO wiadomości (id_od, id_do, tytul, tresc, data) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $sender_id, $recipient_id, $title, $content);
    $stmt->execute();

    echo "Wiadomość została wysłana!";
} else {
    echo "Odbiorca nie znaleziony.";
}

$stmt->close();
$conn->close();