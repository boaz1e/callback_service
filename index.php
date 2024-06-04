<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Supreme Landing Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to Media Supreme</h1>
        <form action="leads.php" method="POST" id="leadForm">
            <input type="hidden" name="add_lead" value="1">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required>
            <label for="note">Note:</label>
            <textarea id="note" name="note" required></textarea>
            <button type="submit">Submit</button>
        </form>
        <div id="error-message" class="error-message"></div>
        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p id="modalMessage"></p>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>

