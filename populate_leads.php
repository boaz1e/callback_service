<?php
require_once 'includes/config.php';

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
