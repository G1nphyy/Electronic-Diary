<?php
require 'db.php'; 
$query = $_GET['query'] ?? '';

$conn = new mysqli($server_name, $user_name, $password, $database);

if (strlen($query) >= 2) {
    $stmt = $conn->prepare(
        "SELECT * FROM schools 
         WHERE nazwa_szkoly LIKE ? OR kod_szkoly LIKE ?"
    );
    $search = "%{$query}%";
    $stmt->bind_param('ss', $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();

    $schools = [];
    while ($school = $result->fetch_assoc()) {
        $schools[] = $school;
    }

    echo json_encode($schools);
}