<?php
require_once '../includes/config.php';
require_once '../models/Lead.php';

class LeadsController {
    private $leadModel;

    public function __construct($db) {
        $this->leadModel = new Lead($db);
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
            $data = ($contentType === "application/json") ? json_decode(file_get_contents("php://input"), true) : $_POST;

            error_log("POST data: " . json_encode($data)); // Log the POST data for debugging

            if (isset($data['add_lead'])) {
                $this->addLeadHandler($data);
            } elseif (isset($data['mark_called'])) {
                $this->markLeadAsCalledHandler($data);
            }
            exit; // Ensure the script ends here
        } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (isset($_GET['lead_id'])) {
                $this->showLeadDetailsHandler();
            } elseif (isset($_GET['filter'])) {
                $this->filterLeadsHandler($_GET['filter']);
            } elseif (isset($_GET['country'])) {
                $this->showLeadsByCountryHandler();
            }
            exit; // Ensure the script ends here
        }
    }

    private function addLeadHandler($data) {
        $firstName = $data['first_name'];
        $lastName = $data['last_name'];
        $email = $data['email'];
        $phoneNumber = $data['phone_number'];
        $ipInfo = getIPInfo();
        $ip = $ipInfo['ip'] ?? 'Unknown';
        $country = $ipInfo['country_name'] ?? 'Unknown';
        $url = $_SERVER['REQUEST_URI'];
        $note = $data['note'];
        $sub1 = $_GET['sub_1'] ?? '';

        if ($this->leadModel->isEmailRegistered($email)) {
            $response = json_encode(['status' => 'email_exists']);
            error_log("Response: " . $response); // Log the response
            echo $response;
        } else {
            $success = $this->leadModel->addLead($firstName, $lastName, $email, $phoneNumber, $ip, $country, $url, $note, $sub1);
            $response = json_encode(['status' => $success ? 'success' : 'error', 'name' => $firstName . ' ' . $lastName]);
            error_log("Response: " . $response); // Log the response
            echo $response;
        }
        exit; // Ensure the script ends here
    }

    private function showLeadDetailsHandler() {
        $lead_id = intval($_GET['lead_id']); // Sanitize input
        $lead = $this->leadModel->getLeadById($lead_id);

        if ($lead) {
            echo json_encode($lead);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Lead not found']);
        }
    }

    private function markLeadAsCalledHandler($data) {
        $lead_id = intval($data['lead_id']); // Sanitize input
        $success = $this->leadModel->markLeadAsCalled($lead_id);
        echo json_encode(['status' => $success ? 'success' : 'error']);
    }

    private function filterLeadsHandler($filter) {
        if ($filter == 'called') {
            $leads = $this->leadModel->getLeads("called = 1");
        } elseif ($filter == 'today') {
            $leads = $this->leadModel->getLeads("DATE(created_at) = CURDATE()");
        }
        echo json_encode($leads);
    }

    private function showLeadsByCountryHandler() {
        $country = $_GET['country']; // Sanitize input as needed
        $leads = $this->leadModel->getLeadsByCountry($country);
        echo json_encode($leads);
    }
}

$controller = new LeadsController($conn);
$controller->handleRequest();
?>
