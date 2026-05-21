<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./assets/images/logo.png">
    <title>High Fidelity</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="./main.js"></script>
   
    <link rel="stylesheet" href="./public/src/style.css">
</head>
<body>

<!-- Navigation bar -->
<?php if (isset($_SESSION['username'])): ?>
  <div class="navbar">
    <ul>
      <li><img class="logo" src="./assets/images/logo.png"/></li>
      <li><a href="index.php">Home</a></li>
      <li><a href="./public/src/albums.php">Albums</a></li>
    </ul>
    <div class="search">
      <input type="text" id="searchbox" placeholder="Search for albums here..." onkeypress="handleKeyPress(event)">
    </div>
    <img id="pfp" src="./public/src/assets/images/pfp.png" alt="Profile Picture">
    <a id="Hsignin" href="./public/src/Profilepage.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
    <a href="./public/src/logout.php">Logout</a>
  </div>

<?php else: ?>
  <div class="navbar">
    <ul>
      <li><img class="logo" src="./public/src/assets/images/logo.png"/></li>
      <li><a href="./public/src/index.php">Home</a></li>
      <li><a href="./public/src/CreateProfile.html">Create Account</a></li>
      <li><a href="./public/src/albums.php">Albums</a></li>
    </ul>
    <div class="search">
      <input type="text" id="searchbox" placeholder="Search for albums here..." onkeypress="handleKeyPress(event)">
    </div>
    <img id="pfp" src="./public/src/assets/images/pfp.png" alt="Profile Picture">
    <a id="Hsignin" href="./public/src/AccountLogin.html">Sign In</a>
  </div>
<?php endif; ?>

<!-- Front page -->
<div id="front">
    <h2 id="homeText">
        Review your favorite albums.
        <br>
        Share your playlists with friends.
        <br>
        Discover new artists.
    </h2>
    <a href="albums.php"><button>GET STARTED</button></a>
</div>

<script>
  document.querySelector("#searchbox").addEventListener("keypress", function (event) {
    if (event.key === 'Enter') {
      const searchTerm = document.querySelector("#searchbox").value;
      localStorage.setItem('term', searchTerm);
      window.location.href = './album-results.php';
    }
  });
</script>

</body>
</html>