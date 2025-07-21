<?php
session_start();
include("db.php");

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart)) {
    header("Location: cart.php");
    exit();
}

$errors = [];
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (empty($name) || empty($email)) {
        $errors[] = "Name and email are required.";
    }

    if (empty($errors)) {
        $total = 0;
        foreach ($cart as $product_id => $qty) {
            $product_query = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
            if ($product = mysqli_fetch_assoc($product_query)) {
                $total += $product['price'] * $qty;
            }
        }

        // Insert order
        $order_sql = "INSERT INTO orders (customer_name, customer_email, total) VALUES ('$name', '$email', $total)";
        if (mysqli_query($conn, $order_sql)) {
            $order_id = mysqli_insert_id($conn);

            // Insert order items
            foreach ($cart as $product_id => $qty) {
                $product_query = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
                if ($product = mysqli_fetch_assoc($product_query)) {
                    $price = $product['price'];
                    $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                 VALUES ($order_id, $product_id, $qty, $price)";
                    mysqli_query($conn, $item_sql);
                }
            }

            // Clear the cart
            unset($_SESSION['cart']);
            $success = "Order placed successfully! Your Order ID is: #" . $order_id;
        } else {
            $errors[] = "Error placing order: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout - Watch Store</title>
  <link rel="stylesheet" href="chackout.css">
</head>
<body>
  <div class="container">
    <h1>Checkout</h1>

    <?php if (!empty($success)): ?>
      <p class="success"><?php echo $success; ?></p>
      <p><a href="index.php">Continue Shopping</a></p>
    <?php else: ?>
      <?php if (!empty($errors)): ?>
        <ul class="error">
          <?php foreach ($errors as $error): ?>
            <li><?php echo $error; ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <form method="POST" action="">
        <label for="name">Full Name:</label>
        <input type="text" name="name" required>

        <label for="email">Email Address:</label>
        <input type="email" name="email" required>

        <button type="submit">Place Order</button>
      </form>

      <br><a href="cart.php">← Back to Cart</a>
    <?php endif; ?>
  </div>
</body>
</html>
