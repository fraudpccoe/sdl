<!-- 25.Build a grocery store application using PHP and MySQL -->

<?php
session_start();

// DB setup
$conn = new mysqli('localhost', 'root', '');
$conn->query("CREATE DATABASE IF NOT EXISTS grocery_store");
$conn->select_db('grocery_store');

// Create products table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0
)");

// Insert dummy products if table is empty
$res = $conn->query("SELECT COUNT(*) AS count FROM products");
$count = $res->fetch_assoc()['count'];
if ($count == 0) {
    $conn->query("INSERT INTO products (name, price, stock) VALUES
        ('Apple', 0.99, 20),
        ('Banana', 0.59, 25),
        ('Carrot', 0.39, 30)
    ");
}

// Add to cart
if (isset($_GET['add'])) {
    $id = (int) $_GET['add'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Remove one
if (isset($_GET['remove'])) {
    $id = (int) $_GET['remove'];
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]--;
        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Clear cart
if (isset($_GET['clear'])) {
    unset($_SESSION['cart']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch products
$result = $conn->query("SELECT * FROM products");
$allProducts = [];
while ($row = $result->fetch_assoc()) {
    $allProducts[$row['id']] = $row;
}
$self = htmlspecialchars($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Grocery Store</title>
  <style>
    body {
      padding: 20px;
    }
    .products, .cart {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    .card {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 15px;
      width: 200px;
    }
    .btn {
      padding: 6px 12px;
      color: #fff;
      background: #28a745;
      border-radius: 4px;
      font-size: 14px;
      margin-right: 5px;
    }
    .btn-warning {
      background: #ffc107;
      color: #000;
    }
    .btn-danger {
      background: #dc3545;
    }
    .btn-sm {
      font-size: 12px;
      padding: 4px 8px;
    }
    ul {
      list-style: none;
      padding: 0;
    }
    li {
      background: #fff;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      display: flex;
    }
    .alert {
      background: #e7f3fe;
      padding: 10px;
      border: 1px solid #b3d8ff;
      color: #31708f;
      border-radius: 5px;
    }
  </style>
</head>
<body>

  <h1>Grocery Store</h1>

  <h2>Products</h2>
  <div class="products">
    <?php foreach ($allProducts as $id => $p): ?>
      <div class="card">
        <h3><?php echo htmlspecialchars($p['name']); ?></h3>
        <p>Price: $<?php echo $p['price']; ?></p>
        <p>Stock: <?php echo $p['stock']; ?></p>
        <a href="<?php echo $self; ?>?add=<?php echo $id; ?>" class="btn">Add to Cart</a>
      </div>
    <?php endforeach; ?>
  </div>

  <hr>

  <h2>🛒 Your Cart</h2>
  <a href="<?php echo $self; ?>?clear=1" class="btn btn-danger">Clear Cart</a>

  <?php if (!empty($_SESSION['cart'])): ?>
    <ul class="cart">
      <?php $total = 0; ?>
      <?php foreach ($_SESSION['cart'] as $id => $qty): ?>
        <?php if (isset($allProducts[$id])): ?>
          <li>
            <div class="item-actions">
              <span><?php echo $allProducts[$id]['name']; ?> (x<?php echo $qty; ?>)</span>
              <div>
                <a href="<?php echo $self; ?>?add=<?php echo $id; ?>" class="btn btn-sm">+</a>
                <a href="<?php echo $self; ?>?remove=<?php echo $id; ?>" class="btn btn-warning btn-sm">-</a>
                <strong>$<?php echo $allProducts[$id]['price'] * $qty; ?></strong>
              </div>
            </div>
          </li>
          <?php $total += $allProducts[$id]['price'] * $qty; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
    <h4>Total: $<?php echo number_format($total, 2); ?></h4>
  <?php else: ?>
    <p>Your cart is empty.</p>
  <?php endif; ?>

</body>
</html>
