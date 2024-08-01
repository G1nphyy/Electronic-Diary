<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    require_once 'db.php';
    $conn = new mysqli($server_name, $user_name, $password, $database);
    $sql = "DELETE FROM zmiany_plan_lekcji WHERE id = '$id'";
    $result = $conn->query($sql);
    if ($result) {
        echo "Action executed successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
} else {
    echo "Invalid request.";
}