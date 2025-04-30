<?php
// Start the session
session_start();
$username = $_SESSION['username'];

// Connect to the database
$host = getenv('host');
$user = getenv('user');
$pass = getenv('pass');
$dbname = getenv('dbname');
$port = getenv('port') ?: 3306;
$db = mysqli_connect($host, $user, $pass, $dbname, $port);?>



<!DOCTYPE html>
<html lang="en">
   <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Title -->
<title>High Fidelity</title>

<!-- jQuery CDN to use fetch()-->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Link to the external JavaScript file -->
<script src="./main.js"></script>


<!-- Link to the external CSS stylesheet -->
<link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navigation bar -->
<?php 
  if (isset($_SESSION['username'])) { 
  
     echo   '  <div class="navbar">
     
     <ul>
            <!-- Website logo -->
            <li><img class="logo" src="./assets/images/logo.png"/> </li>
            <!-- Navigation links -->
            <li><a href="index.php">Home</a></li>
            <li><a href="albums.php">Albums</a></li>
        </ul>

        <!-- Search bar -->
        <div class="search">
            <input type="text" id="searchbox" placeholder="Search for albums here...  " onkeypress="handleKeyPress(event)">
        </div>

 
     <img id="pfp" src="./assets/images/pfp.png" alt="Profile Picture"> <a id="Hsignin" href="./Profilepage.php">' . htmlspecialchars($_SESSION['username']) . '</a>
    <a href="logout.php">Logout</a>
    </div>';
  } else { 
  
      echo     '<div class="navbar"> <ul>
      <!-- Website logo -->
      <li><img class="logo" src="./assets/images/logo.png"/> </li>
      <!-- Navigation links -->
      <li><a href="index.php">Home</a></li>
      <li><a href="CreateProfile.html" >Create Account</a></li>
      <li><a href="albums.php">Albums</a></li>
  </ul>

  <!-- Search bar -->
  <div class="search">
      <input type="text" id="searchbox" placeholder=" Search for albums here... " onkeypress="handleKeyPress(event)">
  </div>


<img id="pfp" src="./assets/images/pfp.png" alt="Profile Picture"> <a id="Hsignin" href="./Profilepage.php">' . htmlspecialchars($_SESSION['username']) . '</a>
<a href="logout.php">Logout</a>
       <a id="Hsignin" href="./AccountLogin.html">Sign In</a>
       </div>';
  }
  ?>


<div class="large-container">
    <div class="large-header">
      <div class="profile-image">
        <img src="./assets/images/pfp.png" alt="Profile Picture">
      </div>
      <div class="profile-info">
        <h1><?php echo $_SESSION['username']; ?></h1> 
        
      </div>
      </div>
      

<?php 
$q2 = "SELECT albumName, review, rating FROM reviews
WHERE user = '$username'";
$ratings = mysqli_query($db, $q2);
?>
<span id="reviewTable">
    
<h2>My Reviews:</h2>
<table border="1">
<tr>
    <th>Album</th>
    <th>Review</th>
    <th>Rating</th>

</tr>
<?php
if($ratings){
while ($row_array = mysqli_fetch_assoc($ratings)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row_array['albumName']) . "</td>";
    echo "<td>" . htmlspecialchars($row_array['review']) . "</td>";
    echo "<td>" . htmlspecialchars($row_array['rating']) . "</td>";
    echo "</tr>";
}}

else{
    echo "<span id='reviewTable'><h3>You have no reviews/ratings<h3></span> <br>";
}


?>

</table></span>
    </div>

    <script>
         
      
        document.querySelector("#searchbox").addEventListener("keypress", function (event) {
            if (event.key === 'Enter') {
              // Get the value of the input field
              const searchTerm = document.querySelector("#searchbox").value;
          
              // Store the search term in localStorage
              localStorage.setItem('term', searchTerm);
          
              // Redirect to the album results page
              window.location.href = './album-results.php';
            }
          });
          
</script>
</body>
</html>