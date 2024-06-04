<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "leads_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add Lead
function addLead($conn, $firstName, $lastName, $email, $phoneNumber, $ip, $country, $url, $note, $sub1) {
    if (isEmailRegistered($conn, $email)) {
        return "email_exists";
    }
    $stmt = $conn->prepare("INSERT INTO leads (first_name, last_name, email, phone_number, ip, country, url, note, sub_1) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $firstName, $lastName, $email, $phoneNumber, $ip, $country, $url, $note, $sub1);
    return $stmt->execute() ? "success" : "error";
}

// Check if Email is Already Registered
function isEmailRegistered($conn, $email) {
    $stmt = $conn->prepare("SELECT id FROM leads WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

// Get Lead by ID
function getLeadById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM leads WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Edit Lead by ID
function markLeadAsCalled($conn, $id) {
    $stmt = $conn->prepare("UPDATE leads SET called = TRUE WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Get Leads by Filter
function getLeads($conn, $filter = null) {
    $query = "SELECT * FROM leads";
    if ($filter) {
        $query .= " WHERE " . $filter;
    }
    return $conn->query($query)->fetch_all(MYSQLI_ASSOC);
}

// Add a new lead (example call)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_lead'])) {
    // Fetch data from POST request and add to database
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $ip = $_SERVER['REMOTE_ADDR']; // Fetch user IP address
    $country = "Unknown"; // Fetch country (can be done with an API call)
    $url = $_SERVER['REQUEST_URI']; // Fetch the full URL with query string
    $note = $_POST['note'];
    $sub1 = $_GET['sub_1'] ?? '';

    $result = addLead($conn, $firstName, $lastName, $email, $phoneNumber, $ip, $country, $url, $note, $sub1);
    
    echo json_encode(['status' => $result, 'name' => $firstName . ' ' . $lastName]);
}
?>
