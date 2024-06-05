<?php
class Lead {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addLead($firstName, $lastName, $email, $phoneNumber, $ip, $country, $url, $note, $sub1) {
        $stmt = $this->db->prepare("INSERT INTO leads (first_name, last_name, email, phone_number, ip, country, url, note, sub_1) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $firstName, $lastName, $email, $phoneNumber, $ip, $country, $url, $note, $sub1);
        return $stmt->execute();
    }

    public function getLeadById($id) {
        $stmt = $this->db->prepare("SELECT * FROM leads WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function markLeadAsCalled($id) {
        $stmt = $this->db->prepare("UPDATE leads SET called = TRUE WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getLeads($filter = null) {
        $query = "SELECT * FROM leads";
        if ($filter) {
            $query .= " WHERE " . $filter;
        }
        return $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    public function getLeadsByCountry($country) {
        $stmt = $this->db->prepare("SELECT * FROM leads WHERE country = ?");
        $stmt->bind_param("s", $country);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function isEmailRegistered($email) {
        $stmt = $this->db->prepare("SELECT id FROM leads WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}
?>
