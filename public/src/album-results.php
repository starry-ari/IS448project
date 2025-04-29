<?php
// Start the session
session_start();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Title -->
    <title>Search Results</title>
     <!-- jQuery CDN to use fetch()-->
     <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
   
 
     <!--CSS stylesheet -->
     <link rel="stylesheet" href="style.css">
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
 
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


<img id="pfp" src="./assets/images/pfp.png" alt="Profile Picture">

       <a id="Hsignin" href="./AccountLogin.html">Sign In</a>
       </div>';
  }
  ?>
      
    </div>
        <div id="quantity">

        </div>
        
        <div id="resultsDiv">
        
        </div>
     <!-- Link to the external JavaScript file -->
     <script src="main.js"></script>
     <script>
        
         var key2 =  localStorage.getItem('apiKey');
         var query =  localStorage.getItem('term');
         console.log(query);
         search(query,key2);

     </script>
</body>
</html>