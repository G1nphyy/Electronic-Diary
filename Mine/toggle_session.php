<?php
session_start();

$_SESSION['show'] = isset($_SESSION['show']) ? !$_SESSION['show'] : true;

header('Content-Type: application/json');
echo json_encode(['success' => true]);