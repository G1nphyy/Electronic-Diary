<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $article_id = isset($data['id']) ? intval($data['id']) : 0;
    if ($article_id > 0) {
        $sql = "UPDATE ogloszenia SET is_popular = CASE WHEN is_popular = 1 THEN 0 ELSE 1 END WHERE id = ?";
        $conn = new mysqli($server_name, $user_name, $password, $database);
        if ($conn->connect_error) {
            die(json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]));
        }
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $article_id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        }

        $stmt->close();
        $conn->close();
    }
}
