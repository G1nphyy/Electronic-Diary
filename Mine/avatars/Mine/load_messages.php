<?php
session_start();
require_once 'db.php';

$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : '';

if (!in_array($category, ['sent', 'received', 'all']) || !is_numeric($user_id)) {
    die("Invalid input");
}

$messages = [];
$sql = '';

switch ($category) {
    case 'sent':
        $sql = "SELECT w.*, CONCAT(u.Imie, ' ', u.Nazwisko, ' [', u.`E-mail`, ']') AS `do`, 
                       CONCAT(u2.Imie, ' ', u2.Nazwisko, ' [', u2.`E-mail`, ']') AS `od`
                FROM wiadomości w 
                JOIN users u ON w.id_do = u.id 
                JOIN users u2 ON w.id_od = u2.id
                WHERE w.id_od = ? 
                ORDER BY w.`data` DESC";
        break;
    case 'received':
        $sql = "SELECT w.*, CONCAT(u.Imie, ' ', u.Nazwisko, ' [', u.`E-mail`, ']') AS `od`, 
                       CONCAT(u2.Imie, ' ', u2.Nazwisko, ' [', u2.`E-mail`, ']') AS `do`
                FROM wiadomości w 
                JOIN users u ON w.id_od = u.id 
                JOIN users u2 ON w.id_do = u2.id
                WHERE w.id_do = ? 
                ORDER BY w.`data` DESC";
        break;
    case 'all':
    default:
        $sql = "(SELECT w.*, 
                        CONCAT(u.Imie, ' ', u.Nazwisko, ' [', u.`E-mail`, ']') AS `od`, 
                        CONCAT(u2.Imie, ' ', u2.Nazwisko, ' [', u2.`E-mail`, ']') AS `do`
                 FROM wiadomości w 
                 JOIN users u ON w.id_od = u.id 
                 JOIN users u2 ON w.id_do = u2.id
                 WHERE w.id_do = ?)
                UNION
                (SELECT w.*, 
                        CONCAT(u2.Imie, ' ', u2.Nazwisko, ' [', u2.`E-mail`, ']') AS `od`, 
                        CONCAT(u.Imie, ' ', u.Nazwisko, ' [', u.`E-mail`, ']') AS `do`
                 FROM wiadomości w 
                 JOIN users u ON w.id_do = u.id 
                 JOIN users u2 ON w.id_od = u2.id
                 WHERE w.id_od = ? )
                ORDER BY `data` DESC";
        break;
}

if ($stmt = $conn->prepare($sql)) {
    if ($category == 'all') {
        $stmt->bind_param('ii', $user_id, $user_id);
    } else {
        $stmt->bind_param('i', $user_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $messages[] = [
                'od' => $row['od'],
                'do' => $row['do'],
                'tytul' => $row['tytul'],
                'tresc' => $row['tresc'],
                'data' => $row['data'],
                'odczytane' => $row['odczytane'],
                'id' => $row['id']
            ];
        }
    }
    $stmt->close();
}


$conn->close();
echo json_encode($messages);
