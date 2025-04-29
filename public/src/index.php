<!-- Arianna Richardson
IS 448 High Fidelity project
 home.html file
-->
<?php session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--Website logo-->
        <link rel="icon" type="image/x-icon" href="./assets/images/logo.png">
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


    <!-- Front page -->
    <div id="front">
        <h2 id="homeText">
            Review your favorite albums.
            <br>
            Share your playlists with friends.
            <br>
            Discover new artists.
        </h2>
        <!-- Get Started button -->
       <a href="albums.php"> <button >GET STARTED</button></a>
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
