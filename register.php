<?php
session_start();
include("db.php"); // contains: $conn = mysqli_connect(...);

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST["name"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];

    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        // Check if email already exists
        $check = mysqli_query($conn, "SELECT id FROM user WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $errors[] = "Email already registered.";
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insert into DB
            $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashedPassword);

            if (mysqli_stmt_execute($stmt)) {
                $success = "Registration successful! You can now <a href='login.php'>login</a>.";
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Watch Store</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
<div class="container">
    <h2>Register</h2>

    <?php if (!empty($success)): ?>
        <p class="success"><?php echo $success; ?></p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <ul class="error">
            <?php foreach ($errors as $e): ?>
                <li><?php echo $e; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>
