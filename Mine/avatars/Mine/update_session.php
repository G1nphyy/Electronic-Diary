<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['selectedIds'])) {
    $_SESSION['selectedIds'] = $data['selectedIds'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    header("Location: change_roles.php?page={$_GET['page']}&search={$_GET['search']}");
}