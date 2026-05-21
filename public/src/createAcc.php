<?php
session_start();

// Connect to the database
$host   = getenv('host');
$user   = getenv('user');
$pass   = getenv('pass');
$dbname = getenv('dbname');
$port   = getenv('port') ?: 3306;

$db = new mysqli($host, $user, $pass, $dbname, $port);
if ($db->connect_error) {
    exit("Error: Could not connect to MySQL - " . $db->connect_error);
}

// Read raw POST values — htmlspecialchars() and mysqli_real_escape_string()
// are not needed here; prepared statements handle injection safely.
// htmlspecialchars() on input can corrupt passwords/usernames with special chars.
$email    = trim($_POST['email']    ?? '');
$username = trim($_POST['user']     ?? '');
$password = trim($_POST['psw']      ?? '');

if (empty($email) || empty($username) || empty($password)) {
    exit("Error: All fields are required.");
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Error: Invalid email address.");
}

// Create the users table if it doesn't exist
$createTable = "
CREATE TABLE IF NOT EXISTS users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    email    VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50)  NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
if (!$db->query($createTable)) {
    exit("Error creating table: " . $db->error);
}

// Check for duplicate email/username using a prepared statement
$stmt = $db->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    // exit so code doesn't fall through; don't echo raw HTML from a PHP backend file
    exit("Error: Email or username is already taken. Please choose a different one.");
}
$stmt->close();

// Hash the password — NEVER store plain text passwords
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

//  prepared statement to insert
$stmt = $db->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $username, $hashedPassword);

if ($stmt->execute()) {
    $_SESSION['user_id']  = $db->insert_id;
    $_SESSION['username'] = $username;
    $_SESSION['email']    = $email;
   

    $stmt->close();
    $db->close();
    header('Location: Profilepage.php');
    exit();
} else {
    $error = $stmt->error;
    $stmt->close();
    $db->close();
    exit("Error: " . $error);
}