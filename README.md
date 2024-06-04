# Media Supreme - Web Developer Exam

## Description

This project is a part of the Fullstack Junior Web Developer position exam. It consists of a landing page to collect client details and save them to a database, with additional functionalities including viewing and editing leads.

## Technologies Used

- PHP
- MySQL
- JavaScript
- HTML
- CSS

## Setup Instructions

1. Download and install XAMPP from [here](https://www.apachefriends.org/index.html).
2. Start Apache and MySQL from the XAMPP control panel.
3. Create a new database named `leads_db` in phpMyAdmin.
4. Import the `leads` table schema provided in the `leads.sql` file.
5. Place the project folder `media_supreme` in the `htdocs` directory of your XAMPP installation.
6. Open your browser and navigate to `http://localhost/media_supreme` to access the landing page.
7. To populate the database with fake leads, run `http://localhost/media_supreme/populate_leads.php`.
8. To access the BackOffice area, navigate to `http://localhost/media_supreme/backoffice.php`. Use the credentials:
   - Username: `admin`
   - Password: `password`

## Files

- `index.php`: Landing page with the form.
- `leads.php`: Backend functionalities for adding, retrieving, and editing leads.
- `style.css`: CSS styling for the landing page and BackOffice.
- `script.js`: JavaScript for handling form submission and modal popup.
- `populate_leads.php`: Script to populate the database with fake leads.
- `backoffice.php`: BackOffice area to view and manage leads.

## Notes

- Ensure XAMPP is running before accessing the project in the browser.
- The BackOffice area allows viewing all leads and marking them as called.
#   l e a d s  
 