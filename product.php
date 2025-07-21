<?php
session_start();
include("db.php");

// Check if product ID is set
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID");
}

$product_id = $_GET['id'];

// Fetch product from DB
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, "SELECT * FROM products");

if (mysqli_num_rows($result) == 0) {
    die("Product not found");
}

$product = mysqli_fetch_assoc($result);

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }

    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $product['name']; ?> - Watch Store</title>
  <link rel="stylesheet" href="product.css">
</head>
<body>
  <div class="container">
    <div class="product-detail">
      <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="300">

      <div class="details">
        <h1><?php echo $product['name']; ?></h1>
        <p class="price">₹<?php echo number_format($product['price'], 2); ?></p>
        <p><?php echo $product['description']; ?></p>

        <form method="post">
          <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>

        <br><a href="index.php">← Back to Products</a>
      </div>
    </div>
  </div>
</body>
</html>
