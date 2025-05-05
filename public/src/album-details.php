<?php
// Start the session
session_start();
/*
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['albumR'])) {
    $albumName = $_GET['albumR'];

    $_SESSION['albumR'] = $albumName;
    echo "Album name received: " . htmlspecialchars($albumName);
    exit; 
    
}
*/

// Connect to the database
$host = getenv('host');
$user = getenv('user');
$pass = getenv('pass');
$dbname = getenv('dbname');
$port = getenv('port') ?: 3306;
$db = mysqli_connect($host, $user, $pass, $dbname, $port);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title -->
    <title>Album Details</title>

    <!-- jQuery CDN to use fetch()-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!--FontAwesome CDN for icons-->
    <script src="https://kit.fontawesome.com/f038da56a4.js" crossorigin="anonymous"></script>
   


    <!--CSS stylesheet -->
    <link rel="stylesheet" href="style.css">
    <!--Prototype library -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js"></script>
    <script src="main.js"></script>
</head>

<body>
    <!-- Navigation bar -->
    <?php 
    if (isset($_SESSION['username'])) { 
        echo '
        <div class="navbar">
            <ul>
                <!-- Website logo -->
                <li><img class="logo" src="./assets/images/logo.png"/> </li>
                <!-- Navigation links -->
                <li><a href="index.php">Home</a></li>
                <li><a href="albums.php">Albums</a></li>
            </ul>

            <!-- Search bar -->
            <div class="search">
                <input type="text" id="searchbox" placeholder="Search for albums here..." onkeypress="handleKeyPress(event)">
            </div>

            <img id="pfp" src="./assets/images/pfp.png" alt="Profile Picture">
            <a id="Hsignin" href="./Profilepage.php">' . htmlspecialchars($_SESSION['username']) . '</a>
            <a href="logout.php">Logout</a>
        </div>';
        include'review.php';
    } else { 
        echo '
        <div class="navbar">
            <ul>
                <!-- Website logo -->
                <li><img class="logo" src="./assets/images/logo.png"/> </li>
                <!-- Navigation links -->
                <li><a href="index.php">Home</a></li>
                <li><a href="CreateProfile.html">Create Account</a></li>
                <li><a href="albums.php">Albums</a></li>
            </ul>

            <!-- Search bar -->
            <div class="search">
                <input type="text" id="searchbox" placeholder="Search for albums here..." onkeypress="handleKeyPress(event)">
            </div>

            <img id="pfp" src="./assets/images/pfp.png" alt="Profile Picture">
            <a id="Hsignin" href="./AccountLogin.html">Sign In</a>
        </div>';
    }
    ?>

    <!-- Main container for album details -->
 

    <div id="container">
        <div id="container3">
            <!-- Album details will be populated here by Spotify API -->
         
            <div id="albumDetails">
                <script>
                          
                    var key2 = localStorage.getItem('apiKey');
                    console.log(key2);
                    details(key2);
                 
                
  
                </script>
                    <form method="post" action="review.php">
    <input type="hidden" id="aN" name="albumName" />
</form>
            </div>
            <div id="container2">
                <h2>Song List:</h2>
                <!--Tracks -->
                <div id="tracks">
                    <ol></ol>
                </div>
            </div>
        </div>

        <?php 

$query = "SELECT * FROM reviews";

$result = mysqli_query($db, $query);

if (!$result) {

    echo '<h2>This album has no reviews yet.</h2>';
}
    else{
        ?>
      
    
 <span id="avgRating"><h3></h3></span>
      <div id='reviewTable'>
     
<table border="1">
<tr>
    <th>User</th>
    <th>Review</th>
    <th>Rating</th>

</tr>
   
    <?php
$albumR = $_POST["albumName"];
$query = "SELECT user, review, rating FROM reviews WHERE albumName = $albumR ";
$result2 = mysqli_query($db, $query);



while ($row_array = mysqli_fetch_assoc($result2)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row_array['user']) . "</td>";
    echo "<td>" . htmlspecialchars($row_array['review']) . "</td>";
    echo "<td>" . htmlspecialchars($row_array['rating']) . "</td>";
    echo "</tr>";
}
    } ?>
 
 
</table>
    </div>

    <?php 
  
    //AVG of rating values
    $query3 = "
    SELECT albumName,
    ROUND(AVG(rating), 2) AS avgRating,
    COUNT(*) AS totalReviews
    FROM reviews
    GROUP BY albumName
    ORDER BY avgRating DESC, totalReviews DESC";

$result3 = mysqli_query($db, $query3);
if($result3){
    if ($row3 = mysqli_fetch_assoc($result3)) {
        echo '<span id="avgRating"><h2>Rating:' . htmlspecialchars($row3['avgRating']) . '</h2></span>';
}

else{
    echo '<span id="avgRating"><h2>This album has no ratings yet.</h2></span>';
}

}
    
    

?>
        <?php if (!isset($_SESSION['username'])) { ?>
            <span id="container4">
                <h2>Please log in or create an account to rate albums.</h2>
                <input type="hidden" id="aN" name="albumName" value="title">
            </span>
        <?php } else { ?>
            <span id="container4">
            
                 

                <form method="POST" action="review.php">
                    <div id="rating" required>
                        <h2>Your Rating:</h2>
                        <input type="hidden" id="aN" name="albumName" value="title">
                        <label><input type="radio" name="star" value="1" id="one" /><i class="fa-regular fa-star"></i></label>
                        <label><input type="radio" name="star" value="2" id="two" /><i class="fa-regular fa-star"></i></label>
                        <label><input type="radio" name="star" value="3" id="three" /><i class="fa-regular fa-star"></i></label>
                        <label><input type="radio" name="star" value="4" id="four" /><i class="fa-regular fa-star"></i></label>
                        <label><input type="radio" name="star" value="5" id="five" /><i class="fa-regular fa-star"></i></label>
                    </div>

                    <span id="AlbumReview">
                        <h1>Write a Review:</h1>
                       
                        <input type="text" name="review" placeholder="Write your review here:" required>
                        <input type="submit" value="Submit"></input>
                    </span>
                </form>
            </span>
        <?php } ?>
    </div>
   

    <script>
        var key2 = localStorage.getItem('apiKey');
        featured(key2);
        document.querySelector("#searchbox").addEventListener("keypress", function (event) {
            if (event.key === 'Enter') {
                const searchTerm = document.querySelector("#searchbox").value;
                localStorage.setItem('term', searchTerm);
                window.location.href = './album-results.php';
            }
        });
    </script>
    <?php mysqli_close($db);?>
</body>
</html>
