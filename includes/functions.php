<?php
// includes/functions.php

function addLead($conn, $firstName, $lastName, $email, $phoneNumber, $ip, $country, $url, $note, $sub1) {
    $stmt = $conn->prepare("INSERT INTO leads (first_name, last_name, email, phone_number, ip, country, url, note, sub_1) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $firstName, $lastName, $email, $phoneNumber, $ip, $country, $url, $note, $sub1);
    return $stmt->execute();
}

function isEmailRegistered($conn, $email) {
    $stmt = $conn->prepare("SELECT id FROM leads WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
}

function getLeadById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM leads WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function markLeadAsCalled($conn, $id) {
    $stmt = $conn->prepare("UPDATE leads SET called = TRUE WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

function getLeads($conn, $filter = null) {
    $query = "SELECT * FROM leads";
    if ($filter) {
        $query .= " WHERE " . $filter;
    }
    return $conn->query($query)->fetch_all(MYSQLI_ASSOC);
}
?>
