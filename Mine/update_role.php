<?php
session_start();
if ($_SESSION['Rola_user'] !== 'Admin') {
    header('Location: zaloguj.php');
    exit();
}

require_once 'db.php';
$conn = new mysqli($server_name, $user_name, $password, $database);

if ($conn->connect_errno != 0) {
    echo "Error: " . $conn->connect_error;
} else {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['new_role'];

    $sql = "UPDATE users SET Rola = ?, Czego_uczy = CASE
            WHEN ? = 'Uczeń' THEN NULL
            ELSE Czego_uczy
        END
        WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $new_role, $new_role, $user_id);

    if ($stmt->execute()) {
        header("Location: change_roles.php?page={$_GET['page']}&search={$_GET['search']}"); 
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();