# Media Supreme Website

This project is a landing page for a media company called "Media Supreme". The landing page allows users to submit their details to be contacted later. It also includes a back-office system to manage and view the leads.

## Installation

1. **Navigate to the project directory:**

   ```sh
   cd media_supreme_website
   ```

2. **Set up the database:**

   **Start XAMPP:**

   - Open XAMPP Control Panel.
   - Start the `Apache` and `MySQL` services.

   **Create the Database:**

- Open your web browser and navigate to `http://localhost/phpmyadmin`.
- In phpMyAdmin, click on the `Databases` tab.
- In the `Create database` field, enter `leads_db` and click the `Create` button.

  **Create the `leads` Table:**

- Click on the `leads_db` database you just created.
- Click on the `SQL` tab.
- Copy and paste the following SQL commands into the SQL query editor and click `Go` to create the `leads` table:

  ```sql
  CREATE TABLE IF NOT EXISTS leads (
      id INT AUTO_INCREMENT PRIMARY KEY,
      first_name VARCHAR(255) NOT NULL,
      last_name VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL,
      phone_number VARCHAR(255) NOT NULL,
      ip VARCHAR(255) NOT NULL,
      country VARCHAR(255) NOT NULL,
      url VARCHAR(255) NOT NULL,
      note TEXT NOT NULL,
      sub_1 TEXT,
      called BOOLEAN DEFAULT FALSE,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );
  ```

3. **Configure your server:**

   - Ensure your web server (Apache) is set up to serve PHP files.
   - Place the project directory in your web server's root directory (e.g., `C:/xampp/htdocs` for XAMPP on Windows).

## Configuration

1. **Database Configuration:**

   Edit the `includes/db.php` file to configure the database connection:

   ```php
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
   ?>
   ```

2. **Other Configurations:**

   Ensure the `includes/config.php` file includes the necessary includes and session start:

   ```php
   <?php
   session_start();
   require_once 'db.php';
   require_once 'functions.php';

   define('BASE_PATH', __DIR__ . '/../');
   ?>
   ```

## Usage

1. **Starting the Server:**

   Ensure your Apache server is running (e.g., using XAMPP, MAMP, etc.).

2. **Accessing the Landing Page:**

   Open your browser and navigate to:

   ```
   http://localhost/media_supreme_website/index.php
   ```

3. **Accessing the BackOffice:**

   Open your browser and navigate to:

   ```
   http://localhost/media_supreme_website/backoffice.php
   ```

   - Login with username: `admin` and password: `password`.

## Endpoints

### Add Lead to the Database

- **URL:** `http://localhost/media_supreme_website/leads.php`
- **Method:** `POST`
- **Body:** (raw JSON)

  ```json
  {
    "add_lead": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "phone_number": "123-456-7890",
    "note": "Interested in services"
  }
  ```

### Show Lead Details by ID

- **URL:** `http://localhost/media_supreme_website/leads.php?lead_id=1`
- **Method:** `GET`

### Edit Lead by ID to Mark as "Called"

- **URL:** `http://localhost/media_supreme_website/leads.php`
- **Method:** `POST`
- **Body:** (raw JSON)

  ```json
  {
    "mark_called": 1,
    "lead_id": 1
  }
  ```

### Show All Leads that are "Called"

- **URL:** `http://localhost/media_supreme_website/leads.php?filter=called`
- **Method:** `GET`

### Show Leads Created Today

- **URL:** `http://localhost/media_supreme_website/leads.php?filter=today`
- **Method:** `GET`

### Show Leads by Country

- **URL:** `http://localhost/media_supreme_website/leads.php?country=USA`
- **Method:** `GET`

#
