<?php
session_start(); // FIXED: was called twice

// Connect to the database
$host   = getenv('host');
$user   = getenv('user');
$pass   = getenv('pass');
$dbname = getenv('dbname');
$port   = getenv('port') ?: 3306;

$db = mysqli_connect($host, $user, $pass, $dbname, $port);
if (!$db) {
    exit("Error - Could not connect to MySQL");
}

// FIXED: Read raw POST values — htmlspecialchars() and mysqli_real_escape_string()
// are not needed here; prepared statements handle injection safely.
$username = trim($_POST['user'] ?? '');
$password = trim($_POST['psw']  ?? '');

if (empty($username)) {
    exit("Please enter your username!");
}
if (empty($password)) {
    exit("Please enter your password!");
}

// FIXED: Use a prepared statement — no raw variables in SQL
$stmt = $db->prepare("SELECT id, username, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // FIXED: Use password_verify() to check against the bcrypt hash from createAcc.php
    if (password_verify($password, $row['password'])) {
        $_SESSION['user_id']  = $row['id'];
        $_SESSION['username'] = $row['username'];

        $stmt->close();
        $db->close();
        header("Location: ./index.php");
        exit();
    } else {
        $stmt->close();
        $db->close();
        exit("Invalid username and/or password.");
    }
} else {
    $stmt->close();
    $db->close();
    exit("Invalid username and/or password.");
}