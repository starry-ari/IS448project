<?php
// Start the session
session_start();	
$username = $_SESSION['username'];


$db = mysqli_connect("studentdb-maria.gl.umbc.edu","arichar1","arichar1","arichar1");
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

