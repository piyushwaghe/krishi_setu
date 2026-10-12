<?php
session_start();
include 'config.php';

if(!isset($_SESSION['id'])){
    die("Please login first");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Customer Dashboard</title>

<style>
body {
    margin:0;
    font-family: Arial;
    background:#f4f6f8;
}

/* NAVBAR */
.navbar {
    background:#1b5e20;
    color:white;
    padding:15px;
    text-align:center;
    font-size:24px;
}

/* GRID */
.grid {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
    padding:20px;
}

/* CARD */
.card {
    background:white;
    padding:15px;
    border-radius:10px;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
    text-align:center;
}

.card h3 {
    margin:10px 0;
}

input {
    width:90%;
    padding:10px;
    margin:10px 0;
}

button {
    width:100%;
    padding:10px;
    background:#2e7d32;
    color:white;
    border:none;
    cursor:pointer;
}

button:hover {
    background:#1b5e20;
}

/* TABLE */
table {
    width:90%;
    margin:20px auto;
    border-collapse:collapse;
    background:white;
}

th, td {
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:center;
}

h2 {
    text-align:center;
    margin-top:20px;
}

/* STATUS */
.delivered {
    color:green;
    font-weight:bold;
}

.pending {
    color:orange;
}
.logout-btn {
    float:right;
    background:red;
    color:white;
    padding:8px 15px;
    text-decoration:none;
    border-radius:5px;
}
</style>

</head>

<body>

<div class="navbar">
    Customer Dashboard
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<!-- ================= PRODUCTS ================= -->
<h2>Available Products</h2>

<div class="grid">

<?php
$product_res = $conn->query("SELECT * FROM products WHERE quantity > 0");

while($row = $product_res->fetch_assoc()){
?>

<form action="cart.php" method="GET" class="card">

<h3><?php echo $row['name']; ?></h3>

<p>Price: ₹<?php echo $row['price']; ?></p>

<p>Available: <?php echo $row['quantity']; ?></p>

<p>Code: <?php echo $row['product_code']; ?></p>

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<input type="number"
       name="qty"
       min="1"
       max="<?php echo $row['quantity']; ?>"
       placeholder="Enter Quantity"
       required>

<button type="submit">Buy Now</button>

</form>

<?php } ?>

</div>

<!-- ================= MY ORDERS ================= -->
<h2>My Orders</h2>

<table>
<tr>
<th>Product</th>
<th>Quantity</th>
<th>Total Price</th>
<th>Status</th>
</tr>

<?php
$order_res = $conn->query("
SELECT o.*, p.name as product
FROM orders o
JOIN products p ON o.product_id = p.id
WHERE o.user_id = {$_SESSION['id']}
ORDER BY o.id DESC
");

while($row = $order_res->fetch_assoc()){
    echo "<tr>
    <td>{$row['product']}</td>
    <td>{$row['quantity']}</td>
    <td>₹{$row['total_price']}</td>
    <td>";

    if($row['status'] == 'Pending'){
        echo "<a href='update_status.php?id={$row['id']}'>
              <button>I Received</button></a>";
    } else {
        echo "<span class='delivered'>✔ Delivered</span>";
    }

    echo "</td></tr>";
}
?>

</table>

</body>
</html>