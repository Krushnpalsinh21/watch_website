<?php
session_start();
include("db.php");

$success = "";
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name        = trim($_POST["name"]);
    $price       = trim($_POST["price"]);
    $description = trim($_POST["description"]);
    $imagePath   = "";

    // Validate inputs
    if (empty($name) || empty($price) || !is_numeric($price)) {
        $error = "Please enter valid product name and price.";
    } else {
        // Handle image upload
        if (!empty($_FILES["image"]["name"])) {
            $targetDir = "images/";
            $imageName = basename($_FILES["image"]["name"]);
            $targetFile = $targetDir . time() . "_" . $imageName;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $imagePath = $targetFile;
            } else {
                $error = "Failed to upload image.";
            }
        }

        // If no error, insert into DB
        if (empty($error)) {
            $stmt = mysqli_prepare($conn, "INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sdss", $name, $price, $description, $imagePath);

            if (mysqli_stmt_execute($stmt)) {
                $success = "Product added successfully!";
            } else {
                $error = "Failed to add product.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product - Admin</title>
    <link rel="stylesheet" href="add_product.css">
</head>
<body>
<div class="container">
    <h2>Add New Product</h2>

    <?php if (!empty($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Product Name:</label>
        <input type="text" name="name" required>

        <label>Price (₹):</label>
        <input type="text" name="price" required>

        <label>Description:</label>
        <textarea name="description"></textarea>

        <label>Product Image:</label>
        <input type="file" name="image">

        <button type="submit">Add Product</button>
    </form>

    <br><a href="dashbord.php">← Back to Dashbord</a>
</div>
</body>
</html>
