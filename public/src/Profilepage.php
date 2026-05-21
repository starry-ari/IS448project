<?php
// Start the session
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: AccountLogin.html");
    exit();
}

$username = $_SESSION['username'];

// Connect to the database
$host   = getenv('host');
$user   = getenv('user');
$pass   = getenv('pass');
$dbname = getenv('dbname');
$port   = getenv('port') ?: 3306;

$db = mysqli_connect($host, $user, $pass, $dbname, $port);
if (!$db) {
    exit("Error - could not connect to MySQL");
}

// FIXED: Use prepared statement to prevent SQL injection
$stmt = $db->prepare("SELECT albumName, review, rating FROM reviews WHERE user = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$ratings = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>High Fidelity</title>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="./main.js"></script>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navigation bar -->
<?php if (isset($_SESSION['username'])): ?>
  <div class="navbar">
    <ul>
      <li><img class="logo" src="./assets/images/logo.png"/></li>
      <li><a href="index.php">Home</a></li>
      <li><a href="albums.php">Albums</a></li>
    </ul>
    <div class="search">
      <input type="text" id="searchbox" placeholder="Search for albums here..." onkeypress="handleKeyPress(event)">
    </div>
    <img id="pfp" src="./assets/images/pfp.png" alt="Profile Picture">
    <a id="Hsignin" href="./Profilepage.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
    <a href="logout.php">Logout</a>
  </div>

<?php else: ?>
  <div class="navbar">
    <ul>
      <li><img class="logo" src="./assets/images/logo.png"/></li>
      <li><a href="index.php">Home</a></li>
      <li><a href="CreateProfile.html">Create Account</a></li>
      <li><a href="albums.php">Albums</a></li>
    </ul>
    <div class="search">
      <input type="text" id="searchbox" placeholder="Search for albums here..." onkeypress="handleKeyPress(event)">
    </div>
    <!-- FIXED: Don't show username/logout when user is not logged in -->
    <a id="Hsignin" href="./AccountLogin.html">Sign In</a>
  </div>
<?php endif; ?>

<div class="large-container">
  <div class="large-header">
    <div class="profile-image">
      <img src="./assets/images/pfp.png" alt="Profile Picture">
    </div>
    <div class="profile-info">
      <h1><?php echo htmlspecialchars($username); ?></h1>
    </div>
  </div>

  <span id="reviewTable">
    <h2>My Reviews:</h2>
    <?php if ($ratings && $ratings->num_rows > 0): ?>
      <table border="1">
        <tr>
          <th>Album</th>
          <th>Review</th>
          <th>Rating</th>
        </tr>
        <?php while ($row = $ratings->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['albumName']); ?></td>
            <td><?php echo htmlspecialchars($row['review']); ?></td>
            <td><?php echo htmlspecialchars($row['rating']); ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
    <?php else: ?>
      <!-- FIXED: closing </h3> tag was malformed -->
      <h3>You have no reviews/ratings</h3>
    <?php endif; ?>
  </span>
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