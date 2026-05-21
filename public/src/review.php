<?php
// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Connect to the database
$host   = getenv('host');
$user   = getenv('user');
$pass   = getenv('pass');
$dbname = getenv('dbname');
$port   = getenv('port') ?: 3306;

$db = new mysqli($host, $user, $pass, $dbname, $port);
if ($db->connect_error) {
    exit("Error - could not connect to MySQL");
}

$createTable = "
CREATE TABLE IF NOT EXISTS reviews (
    user       CHAR(100),
    albumName  CHAR(100),
    review     TEXT,
    rating     INT(5)
)";

if (!$db->query($createTable)) {
    exit("Error creating table: " . $db->error);
}
// REMOVED: duplicate mysqli_query($db, $createTable) call

// FIXED: $username was undefined — now pulled from session
$username  = $_SESSION['username'] ?? '';

// FIXED: Use prepared statement to prevent SQL injection
$stmt = $db->prepare(
    "INSERT INTO reviews (user, albumName, review, rating) VALUES (?, ?, ?, ?)"
);

if (!$stmt) {
    exit("Prepare failed: " . $db->error);
}

$albumName = $_POST['albumName'] ?? '';
$review    = $_POST['review']    ?? '';
$rating    = $_POST['star']      ?? 0;

// "sssi" = three strings, one integer
$stmt->bind_param("sssi", $username, $albumName, $review, $rating);

if (!$stmt->execute()) {
    echo "Error inserting review: " . $stmt->error;
} else {
    $stmt->close();
    $db->close();
    header("Location: Profilepage.php");
    exit();
}

$stmt->close();
$db->close();