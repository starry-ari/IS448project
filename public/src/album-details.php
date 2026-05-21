<?php
// FIXED: $albumR and $db were undefined — they must be set before this snippet.
// Assuming $albumR comes from a GET parameter and $db is the mysqli connection.
session_start();

$host   = getenv('host');
$user   = getenv('user');
$pass   = getenv('pass');
$dbname = getenv('dbname');
$port   = getenv('port') ?: 3306;

$db = new mysqli($host, $user, $pass, $dbname, $port);
if ($db->connect_error) {
    exit("Error - Could not connect to MySQL");
}

// FIXED: $albumR was used raw in SQL — get it safely from GET
$albumR = $_GET['albumName'] ?? '';
if (empty($albumR)) {
    exit("No album specified.");
}
?>

<span id="avgRating"><h3></h3></span>
<div id="reviewTable">
  <table border="1">
    <tr>
      <th>User</th>
      <th>Review</th>
      <th>Rating</th>
    </tr>

    <?php
    // FIXED: Use prepared statement instead of raw $albumR in query
    $stmt = $db->prepare("SELECT user, review, rating FROM reviews WHERE albumName = ?");
    $stmt->bind_param("s", $albumR);
    $stmt->execute();
    $result2 = $stmt->get_result();

    if ($result2->num_rows > 0) {
        while ($row = $result2->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['user'])   . "</td>";
            echo "<td>" . htmlspecialchars($row['review']) . "</td>";
            echo "<td>" . htmlspecialchars($row['rating']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No reviews yet.</td></tr>";
    }
    // FIXED: stray closing brace `}` removed from original
    $stmt->close();
    ?>
  </table>
</div>

<?php
// FIXED: Query now filters by the specific album instead of returning all albums
$stmt2 = $db->prepare("
    SELECT albumName,
           ROUND(AVG(rating), 2) AS avgRating,
           COUNT(*) AS totalReviews
    FROM reviews
    WHERE albumName = ?
    GROUP BY albumName
");
$stmt2->bind_param("s", $albumR);
$stmt2->execute();
$result3 = $stmt2->get_result();
$row3 = $result3->fetch_assoc();

if ($row3) {
    echo '<span id="avgRating"><h2>Rating: ' . htmlspecialchars($row3['avgRating']) . ' (' . htmlspecialchars($row3['totalReviews']) . ' reviews)</h2></span>';
} else {
    echo '<span id="avgRating"><h2>This album has no ratings yet.</h2></span>';
}
$stmt2->close();
?>

<?php if (!isset($_SESSION['username'])): ?>
  <span id="container4">
    <h2>Please log in or create an account to rate albums.</h2>
  </span>
<?php else: ?>
  <span id="container4">
    <form method="POST" action="review.php">
      <!-- FIXED: value was hardcoded as "title" — now uses the actual album name -->
      <input type="hidden" name="albumName" value="<?php echo htmlspecialchars($albumR); ?>">
      <div id="rating" required>
        <h2>Your Rating:</h2>
        <label><input type="radio" name="star" value="1"><i class="fa-regular fa-star"></i></label>
        <label><input type="radio" name="star" value="2"><i class="fa-regular fa-star"></i></label>
        <label><input type="radio" name="star" value="3"><i class="fa-regular fa-star"></i></label>
        <label><input type="radio" name="star" value="4"><i class="fa-regular fa-star"></i></label>
        <label><input type="radio" name="star" value="5"><i class="fa-regular fa-star"></i></label>
      </div>
      <span id="AlbumReview">
        <h1>Write a Review:</h1>
        <input type="text" name="review" placeholder="Write your review here..." required>
        <!-- FIXED: <input> is self-closing, can't have </input> -->
        <input type="submit" value="Submit">
      </span>
    </form>
  </span>
<?php endif; ?>

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

<?php mysqli_close($db); ?>