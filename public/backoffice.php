<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../controllers/LoginController.php';

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$controller = new LoginController($conn);
$controller->handleRequest();
?>
