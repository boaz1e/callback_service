<?php
require_once '../includes/config.php';
require_once '../models/Lead.php';

class LoginController {
    private $leadModel;

    public function __construct($db) {
        $this->leadModel = new Lead($db);
    }

    public function handleRequest() {
        // Start the session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
            $this->loginHandler();
        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_called'])) {
            $this->markLeadAsCalledHandler();
        } elseif (isset($_GET['logout'])) {
            $this->logoutHandler();
        } else {
            $this->showBackOffice();
        }
    }

    private function loginHandler() {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        if ($user == 'admin' && $pass == 'password') {
            $_SESSION['loggedin'] = true;
            header("Location: backoffice.php");
        } else {
            $_SESSION['login_error'] = "Invalid credentials.";
            header("Location: backoffice.php");
        }
        exit();
    }

    private function markLeadAsCalledHandler() {
        $id = $_POST['lead_id'];
        $this->leadModel->markLeadAsCalled($id);
        header("Location: backoffice.php");
        exit();
    }

    private function logoutHandler() {
        session_destroy();
        header("Location: backoffice.php");
        exit();
    }

    private function showBackOffice() {
        $leads = $this->leadModel->getLeads();
        include '../views/header.php';

        if (!isset($_SESSION['loggedin'])) {
            include '../views/login.php';
        } else {
            include '../views/leads_table.php';
        }

        include '../views/footer.php';
    }
}
?>
