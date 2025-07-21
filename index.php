<?php
include("db.php");

// Fetch watches from database
$query = "SELECT * FROM watche_website";
$result = mysqli_query($conn, "SELECT * FROM watches");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Watch Store - Home</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Header -->
  <header class="main-header">
    <div class="container">
      <h1>⌚ Watch Store</h1>
      <nav>
        <ul>
          <li><a href="index.php" class="active">Home</a></li>
          <li><a href="cart.php">Cart</a></li>
          <li><a href="login.php">Login</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Product Section -->
  <section class="products">
    <div class="container">
      <h2>Our Watches</h2>
      <div class="product-grid">
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
          <div class="product-card">
            <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            <h3><?php echo $row['name']; ?></h3>
            <p>₹<?php echo $row['price']; ?></p>
            <form action="add_to_cart.php" method="post">
              <input type="hidden" name="watch_id" value="<?php echo $row['id']; ?>">
              <input type="submit" value="Add to Cart">
            </form>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="main-footer">
    <div class="container">
      <p>&copy; 2025 Watch Store. All rights reserved.</p>
    </div>
  </footer>

</body>
</html>
