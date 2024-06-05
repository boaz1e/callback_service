<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';


// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if ($contentType === "application/json") {
        // Read JSON input
        $data = json_decode(file_get_contents("php://input"), true);
    } else {
        // Read form input
        $data = $_POST;
    }

    error_log("POST data: " . json_encode($data)); // Log the POST data for debugging

    if (isset($data['add_lead'])) {
        addLeadHandler($data);
    } elseif (isset($data['mark_called'])) {
        markLeadAsCalledHandler($data);
    }
    exit; // Ensure the script ends here
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
      $response = json_encode(['status' => 'email_exists']);
      error_log("Response: " . $response); // Log the response
      echo $response;
  } else {
      $success = addLead($conn, $firstName, $lastName, $email, $phoneNumber, $ip, $country, $url, $note, $sub1);
      $response = json_encode(['status' => $success ? 'success' : 'error', 'name' => $firstName . ' ' . $lastName]);
      error_log("Response: " . $response); // Log the response
      echo $response;
  }
  exit; // Ensure the script ends here
}



// Function to handle showing lead details by ID
function showLeadDetailsHandler() {
    global $conn;
    $lead_id = intval($_GET['lead_id']); // Sanitize input
    $lead = getLeadById($conn, $lead_id);

    if ($lead) {
        echo json_encode($lead);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lead not found']);
    }
}

// Function to handle marking a lead as called
function markLeadAsCalledHandler($data) {
    global $conn;
    $lead_id = intval($data['lead_id']); // Sanitize input
    $success = markLeadAsCalled($conn, $lead_id);
    echo json_encode(['status' => $success ? 'success' : 'error']);
}

// Function to handle showing all called leads
function showCalledLeadsHandler() {
    global $conn;
    $leads = getLeads($conn, "called = 1");
    echo json_encode($leads);
}

// Function to handle showing leads created today
function showTodayLeadsHandler() {
  global $conn;
  $leads = getLeads($conn, "DATE(created_at) = CURDATE()");
  echo json_encode($leads);
}

// Function to handle showing leads by country
function showLeadsByCountryHandler() {
    global $conn;
    $country = $_GET['country']; // Sanitize input as needed
    $leads = getLeads($conn, "country = '" . $conn->real_escape_string($country) . "'");
    echo json_encode($leads);
}

// Include the existing functions from functions.php
require_once 'includes/functions.php';

