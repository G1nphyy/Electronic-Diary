<?php
session_start();

// Toggle 'show' session variable
$_SESSION['show'] = isset($_SESSION['show']) ? !$_SESSION['show'] : true;

// Return JSON response
header('Content-Type: application/json');
echo json_encode(['success' => true]);