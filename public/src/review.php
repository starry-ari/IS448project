<?php
// Start the session
session_start();	
$username = $_SESSION['username'];
$host = getenv('host');
$user = getenv('user');
$pass = getenv('pass');
$dbname = getenv('dbname');
$port = getenv('port') ?: 3306;

$dbConn = new mysqli($host, $user, $pass, $dbname, $port);
$review = $_POST['review'];
$albumName = $_POST['albumName'];
$rating = $_POST['star'];




if (mysqli_connect_errno())
exit("Error - could not connect to MySQL");

$createTable = "
CREATE TABLE IF NOT EXISTS reviews (
    user CHAR(100),
    albumName CHAR(100), 
    review TEXT,
    rating INT(5)
)";

if (!mysqli_query($db, $createTable)) {
    exit("Error creating table: " . mysqli_error($db));
}



$q = "INSERT INTO reviews (user, albumName, review, rating)
VALUES ('$username', '$albumName','$review','$rating' )";

if (!mysqli_query($db, $q)) {
    echo "Error inserting review: " . mysqli_error($db);
} else {
    header("Location: Profilepage.php");
  exit();
}









mysqli_close($db);

