<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  header('Location: session.php');
  exit();
}

// Connect to the database
$db = new SQLite3('database.sqlite');

// Handle the user's form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Get the user's ID from the session
  $username = $_SESSION['username'];

  // Get the user's input
  $desc = htmlspecialchars($_POST['desc']);

  // Update the user's profile description in the database
  $stmt = $db->prepare('UPDATE users SET desc = :desc WHERE username = :username');
  $stmt->bindValue(':desc', $desc, SQLITE3_TEXT);
  $stmt->bindValue(':username', $username, SQLITE3_TEXT);
  $stmt->execute();

  // Redirect to the profile page
  header('Location: desc.php');
  exit();
}

// Get the user's current profile description from the database
$username = $_SESSION['username'];
$stmt = $db->prepare('SELECT desc FROM users WHERE username = :username');
$stmt->bindValue(':username', $username, SQLITE3_TEXT);
$result = $stmt->execute();
$row = $result->fetchArray(SQLITE3_ASSOC);
$current_desc = htmlspecialchars($row['desc']);

// Close the database connection
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Description</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
  <div class="container">
    <h3 class="mt-4 text-center fw-bold text-secondary">Edit Description</h3>
    <form method="POST">
      <div class="mb-3">
        <label for="desc" class="form-label text-secondary fw-bold">Description:</label>
        <textarea class="form-control" id="desc" name="desc" rows="5" maxlength="400"><?php echo htmlspecialchars($current_desc); ?></textarea>
      </div>
      <header class="d-flex justify-content-center py-3">
        <ul class="nav nav-pills">
          <li class="nav-item"><button type="submit" class="btn btn-primary fw-bold">Save</button></li>
          <li class="nav-item"><a href="setting.php" class="btn btn-danger ms-1 fw-bold">Back</a></li>
        </ul>
      </header>
    </form>
  </div>
</body>
</html>
