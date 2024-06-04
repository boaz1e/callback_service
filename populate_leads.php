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

function addLead($conn, $firstName, $lastName, $email, $phoneNumber, $ip, $country, $url, $note, $sub1) {
    $stmt = $conn->prepare("INSERT INTO leads (first_name, last_name, email, phone_number, ip, country, url, note, sub_1) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $firstName, $lastName, $email, $phoneNumber, $ip, $country, $url, $note, $sub1);
    return $stmt->execute();
}

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://jsonplaceholder.typicode.com/users");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($curl);
curl_close($curl);

$users = json_decode($response, true);

foreach ($users as $user) {
    $nameParts = explode(' ', $user['name']);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
    $email = $user['email'];
    $phoneNumber = $user['phone'];
    $ip = '127.0.0.1';
    $country = $user['address']['city'];
    $url = 'http://example.com';
    $note = 'Fake user';
    $sub1 = 'fake_sub1';

    addLead($conn, $firstName, $lastName, $email, $phoneNumber, $ip, $country, $url, $note, $sub1);
}

echo "Database populated with fake leads!";
?>
