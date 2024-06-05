<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../controllers/LeadsController.php';

$controller = new LeadsController($conn);
$controller->handleRequest();
?>
