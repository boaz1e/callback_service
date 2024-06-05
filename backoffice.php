<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    if ($user == 'admin' && $pass == 'password') {
        $_SESSION['loggedin'] = true;
    } else {
        $login_error = "Invalid credentials.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_called'])) {
    $id = $_POST['lead_id'];
    markLeadAsCalled($conn, $id);
}

$leads = getLeads($conn);

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: backoffice.php");
}

include BASE_PATH . 'views/header.php';

if (!isset($_SESSION['loggedin'])) {
    include BASE_PATH . 'views/login.php';
} else {
    include BASE_PATH . 'views/leads_table.php';
}

include BASE_PATH . 'views/footer.php';
?>
