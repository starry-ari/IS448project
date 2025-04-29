<?php
// Start the session
session_start();

// Connect to the RDS database
$host = getenv('DB_HOST');  
$port = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

$dbConn = new mysqli($host, $user, $pass, $dbname, $port);

$dbConn= mysqli_connect("studentdb-maria.gl.umbc.edu", "arichar1", "arichar1", "arichar1");

if ($dbConn->connect_error) {
    die("Error: Could not connect to MySQL - " . $dbConn->connect_error );
}


$email = htmlspecialchars($_POST['email']);
$username = htmlspecialchars($_POST['user']);
$password = htmlspecialchars($_POST['psw']);


$email = mysqli_real_escape_string($dbConn, $email);
$username = mysqli_real_escape_string($dbConn, $username);
$password = mysqli_real_escape_string($dbConn, $password);


// Validate input (basic example)
if (empty($email) || empty($username) || empty($password)) {
    exit("Error: All fields are required.");
}




// Create the `users` table if it does not exist
$createTable = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
mysqli_query($dbConn, $createTable);

if (mysqli_error($dbConn)) {
    die("Error creating table: " . mysqli_error($dbConn));
}

// Check if the username already exists
$sameUser = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
$result = mysqli_query($dbConn, $sameUser);

if (mysqli_num_rows($result) > 0) {
    echo "<span id='error'>Error: Username is already taken. Please choose a different username.</span>";
}
else{


// Insert user data into the database

$query = "INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$password')";

if (mysqli_query($dbConn, $query)) {

    $_SESSION['user_id'] = mysqli_insert_id($dbConn);
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;

 // Redirect to a welcome page
 header('Location: Profilepage.php');
    exit();
    
} else {
    die("Error: " . mysqli_error($dbConn));
}

}
