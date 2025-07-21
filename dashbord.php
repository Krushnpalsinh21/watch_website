<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard - Watch Store</title>
</head>
<body>
  <h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
  <p>This is your dashboard.</p>
  <a href="logout.php">Logout</a>
</body>
</html>
