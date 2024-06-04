<?php
session_start();

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

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    if ($user == 'admin' && $pass == 'password') {
        $_SESSION['loggedin'] = true;
    } else {
        $login_error = "Invalid credentials.";
    }
}

// Handle mark as called
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_called'])) {
    $id = $_POST['lead_id'];
    markLeadAsCalled($conn, $id);
}

// Get all leads
$leads = getLeads($conn);

// Mark lead as called
function markLeadAsCalled($conn, $id) {
    $stmt = $conn->prepare("UPDATE leads SET called = TRUE WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Get all leads
function getLeads($conn) {
    return $conn->query("SELECT * FROM leads")->fetch_all(MYSQLI_ASSOC);
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: backoffice.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BackOffice - Media Supreme</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php if (!isset($_SESSION['loggedin'])): ?>
            <h2>Login</h2>
            <form action="backoffice.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit" name="login">Login</button>
                <?php if (isset($login_error)): ?>
                    <p class="error-message"><?php echo $login_error; ?></p>
                <?php endif; ?>
            </form>
        <?php else: ?>
            <h2>Leads</h2>
            <a href="backoffice.php?logout=1">Logout</a>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>IP</th>
                            <th>Country</th>
                            <th>URL</th>
                            <th>Note</th>
                            <th>Sub_1</th>
                            <th>Called</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($leads as $lead): ?>
                            <tr>
                                <td><?php echo $lead['id']; ?></td>
                                <td><?php echo $lead['first_name']; ?></td>
                                <td><?php echo $lead['last_name']; ?></td>
                                <td><?php echo $lead['email']; ?></td>
                                <td><?php echo $lead['phone_number']; ?></td>
                                <td><?php echo $lead['ip']; ?></td>
                                <td><?php echo $lead['country']; ?></td>
                                <td><?php echo $lead['url']; ?></td>
                                <td><?php echo $lead['note']; ?></td>
                                <td><?php echo $lead['sub_1']; ?></td>
                                <td><?php echo $lead['called'] ? 'Yes' : 'No'; ?></td>
                                <td><?php echo $lead['created_at']; ?></td>
                                <td>
                                    <?php if (!$lead['called']): ?>
                                        <form action="backoffice.php" method="POST">
                                            <input type="hidden" name="lead_id" value="<?php echo $lead['id']; ?>">
                                            <button type="submit" name="mark_called">Mark as Called</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
