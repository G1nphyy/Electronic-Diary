<?php
session_start();

require_once 'db.php';

if (isset($_POST['id_article']) && !empty($_POST['id_article'])) {
    $conn = new mysqli($server_name, $user_name, $password, $database);
    $article_id = filter_var($_POST['id_article'], FILTER_SANITIZE_NUMBER_INT);

    $sql = "DELETE FROM ogloszenia WHERE id = '$article_id'";
    $result = $conn->query($sql);
}else{
    echo "Error";
}

header("Location: index.php");
exit();
