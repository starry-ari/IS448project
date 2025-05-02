<?php

session_start();
// Connect to the database
$host = getenv('host');
$user = getenv('user');
$pass = getenv('pass');
$dbname = getenv('dbname');
$port = getenv('port') ?: 3306;
$db = new mysqli($host, $user, $pass, $dbname, $port);?>

<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Title -->
    <title>Discover Albums</title>
     <!-- jQuery CDN to use fetch()-->
     <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   
 
     <!--CSS stylesheet -->
     <link rel="stylesheet" href="style.css">
    <!-- Link to the external JavaScript file -->
     <script src="main.js"></script>
</head>
<body>   <?php 
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


<img id="pfp" src="./assets/images/pfp.png" alt="Profile Picture"> <a id="Hsignin" href="./Profilepage.php">

       <a id="Hsignin" href="./AccountLogin.html">Sign In</a>
       </div>';
  }
  ?>
<script>   var key2 =  localStorage.getItem('apiKey');
         featured(key2);</script>


<!-- New album section-->
<div id="homeAlbums">
<h3>Top Albums Today:</h3>
<div id="popAlbumList">
     <!-- Top album releases will be populated here by Spotify API -->
 </div>
    <h3>Discover:</h3>
    <div id="newAlbumList">
        <!-- new album releases will be populated here by Spotify API -->
    </div>
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
  