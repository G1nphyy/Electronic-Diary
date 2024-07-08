<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'db.php';
    $conn = new mysqli($server_name, $user_name, $password, $database);

    $selectedIds = $_POST['selectedIds'];
    $data = $_POST['data'];
    $czas = $_POST['czas'];
    $lekcja = $_POST['lekcja'];
    $attendance_status = $_POST['attendance_status'];
    $dataandczas = $data." ".$czas;
    foreach ($attendance_status as $ID => $status) {
        $ID = mysqli_real_escape_string($conn, $ID);
        $status = mysqli_real_escape_string($conn, $status);

        $sql = "INSERT INTO `attendance` (`id`, `user_id`, `date`, `status`, `lekcja`) VALUES (NULL, '$ID', '$dataandczas', '$status', '$lekcja') ";
        if ($conn->query($sql) !== TRUE) {
            echo "Error updating attendance: " . $conn->error;
        }
    }

    $conn->close();

    header('Location: frekfencja.php');
    exit;
} else {
    header('Location: index.php');
    exit;
}