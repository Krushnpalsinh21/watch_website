<?php
session_start();
include("db.php");

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Escape user input to prevent SQL Injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // SQL Query
    $sql = "SELECT * FROM users WHERE email='$email' AND password=MD5('$password')";
    
    $query=mysqli_query($conn, "SELECT * FROM user");


    // Check if query ran successfully
    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $login_error = "Invalid email or password!";
        }
    } else {
        // Query failed
        $login_error = "Database query failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Watch Store</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>

  <div class="login-container">
    <h2>Login</h2>
    <?php if (!empty($login_error)): ?>
      <p class="error"><?php echo $login_error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
  </div>

</body>
</html>
