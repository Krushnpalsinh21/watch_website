<?php
session_start();
include("db.php");

// Remove item
if (isset($_GET['remove']) && isset($_SESSION['cart'][$_GET['remove']])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    header("Location: cart.php");
    exit();
}

// Cart item IDs
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cart - Watch Store</title>
  <link rel="stylesheet" href="cart.css">
</head>
<body>

  <div class="container">
    <h1>Your Shopping Cart</h1>

    <?php if (empty($cart)): ?>
      <p>Your cart is empty.</p>
    <?php else: ?>
      <table class="cart-table">
        <thead>
          <tr>
            <th>Watch</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Remove</th>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ($cart as $product_id => $qty):
            $query = "SELECT * FROM products WHERE id = $product_id";
           $query= mysqli_query($conn, "SELECT * FROM watches");

            if ($row = mysqli_fetch_assoc($result)):
              $subtotal = $row['price'] * $qty;
              $total += $subtotal;
          ?>
          <tr>
            <td><?php echo $row['name']; ?></td>
            <td>₹<?php echo number_format($row['price'], 2); ?></td>
            <td><?php echo $qty; ?></td>
            <td>₹<?php echo number_format($subtotal, 2); ?></td>
            <td><a href="cart.php?remove=<?php echo $product_id; ?>">Remove</a></td>
          </tr>
          <?php endif; endforeach; ?>
        </tbody>
      </table>

      <h3>Total: ₹<?php echo number_format($total, 2); ?></h3>
      <a href="checkout.php" class="btn">Proceed to Checkout</a>
    <?php endif; ?>

    <br><a href="index.php">← Continue Shopping</a>
  </div>

</body>
</html>
