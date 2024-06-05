<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../models/lead_model.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the content type is JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if ($contentType === "application/json") {
        $data = json_decode(file_get_contents("php://input"), true);
    } else {
        $data = $_POST;
    }

    error_log("POST data: " . json_encode($data));

    if (isset($data['add_lead'])) {
        addLeadHandler($data);
    } elseif (isset($data['mark_called'])) {
        markLeadAsCalledHandler($data);
    }
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['lead_id'])) {
        showLeadDetailsHandler();
    } elseif (isset($_GET['filter'])) {
        if ($_GET['filter'] == 'called') {
            showCalledLeadsHandler();
        } elseif ($_GET['filter'] == 'today') {
            showTodayLeadsHandler();
        }
    } elseif (isset($_GET['country'])) {
        showLeadsByCountryHandler();
    }
}

function addLeadHandler($data) {
    global $conn;
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

    if (isEmailRegistered($conn, $email)) {
        $response = ['status' => 'email_exists'];
        error_log("Response: " . json_encode($response));
        echo json_encode($response);
    } else {
        $success = addLead($conn, $firstName, $lastName, $email, $phoneNumber, $ip, $country, $url, $note, $sub1);
        $response = ['status' => $success ? 'success' : 'error', 'name' => $firstName . ' ' . $lastName];
        error_log("Response: " . json_encode($response));
        echo json_encode($response);
    }
    exit;
}

function showLeadDetailsHandler() {
    global $conn;
    $lead_id = intval($_GET['lead_id']);
    $lead = getLeadById($conn, $lead_id);

    if ($lead) {
        echo json_encode($lead);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lead not found']);
    }
}

function markLeadAsCalledHandler($data) {
    global $conn;
    $lead_id = intval($data['lead_id']);
    $success = markLeadAsCalled($conn, $lead_id);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

function showCalledLeadsHandler() {
    global $conn;
    $leads = getLeads($conn, "called = 1");
    echo json_encode($leads);
}

function showTodayLeadsHandler() {
    global $conn;
    $leads = getLeads($conn, "DATE(created_at) = CURDATE()");
    echo json_encode($leads);
}

function showLeadsByCountryHandler() {
    global $conn;
    $country = $_GET['country'];
    $leads = getLeads($conn, "country = '" . $conn->real_escape_string($country) . "'");
    echo json_encode($leads);
}
