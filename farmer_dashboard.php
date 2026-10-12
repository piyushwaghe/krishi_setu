<?php
session_start();
include 'config.php';

if(!isset($_SESSION['id'])){
    die("Please login first");
}

$fid = $_SESSION['id'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Farmer Dashboard</title>

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
    font-size:24px;
    text-align:center;
}

/* CONTAINER */
.container {
    width:90%;
    margin:auto;
    padding:20px;
}

/* CARDS */
.cards {
    display:flex;
    gap:20px;
    margin-bottom:30px;
}

.card {
    flex:1;
    background:white;
    padding:20px;
    border-radius:10px;
    text-align:center;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

.card h2 {
    margin:10px 0;
}

/* BUTTON */
.btn {
    display:inline-block;
    padding:12px 20px;
    background:#2e7d32;
    color:white;
    text-decoration:none;
    border-radius:5px;
    margin-bottom:20px;
}

/* TABLE */
table {
    width:100%;
    border-collapse:collapse;
    background:white;
    margin-bottom:30px;
}

th, td {
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:center;
}

h2 {
    margin:15px 0;
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
    Farmer Dashboard
    <a href="logout.php" class="logout-btn">Logout</a>
</div>



<!-- DASHBOARD CARDS -->
<div class="cards">

<div class="card">
<h3>Total Products</h3>
<?php
$res = $conn->query("SELECT COUNT(*) as total FROM products WHERE farmer_id=$fid");
echo "<h2>".$res->fetch_assoc()['total']."</h2>";
?>
</div>

<div class="card">
<h3>Total Orders</h3>
<?php
$res = $conn->query("SELECT COUNT(*) as total 
FROM orders o 
JOIN products p ON o.product_id=p.id 
WHERE p.farmer_id=$fid");
echo "<h2>".$res->fetch_assoc()['total']."</h2>";
?>
</div>

<div class="card">
<h3>Total Quantity Sold</h3>
<?php
$res = $conn->query("SELECT SUM(o.quantity) as total 
FROM orders o 
JOIN products p ON o.product_id=p.id 
WHERE p.farmer_id=$fid");

$data = $res->fetch_assoc()['total'];
echo "<h2>".($data ? $data : 0)."</h2>";
?>
</div>

</div>

<!-- ADD PRODUCT -->
<a href="add_product.php" class="btn">+ Add Product</a>

<!-- MY PRODUCTS -->
<h2>My Products</h2>

<table>
<tr>
<th>Name</th>
<th>Price</th>
<th>Quantity</th>
<th>Product Code</th>
</tr>

<?php
$res = $conn->query("SELECT * FROM products WHERE farmer_id=$fid");

while($row = $res->fetch_assoc()){
    echo "<tr>
    <td>{$row['name']}</td>
    <td>₹{$row['price']}</td>
    <td>{$row['quantity']}</td>
    <td>{$row['product_code']}</td>
    </tr>";
}
?>
</table>

<!-- ORDERS RECEIVED -->
<h2>Orders Received</h2>

<table>
<tr>
<th>Customer</th>
<th>Address</th>
<th>Product</th>
<th>Quantity</th>
<th>Total Price</th>
<th>Status</th>
</tr>

<?php
$res = $conn->query("
SELECT o.*, u.name as customer, u.address, p.name as product
FROM orders o
JOIN users u ON o.user_id = u.id
JOIN products p ON o.product_id = p.id
WHERE p.farmer_id = $fid
ORDER BY o.id DESC
");

while($row = $res->fetch_assoc()){
    echo "<tr>
    <td>{$row['customer']}</td>
    <td>{$row['address']}</td>
    <td>{$row['product']}</td>
    <td>{$row['quantity']}</td>
    <td>₹{$row['total_price']}</td>
    <td>";

    if($row['status'] == 'Delivered'){
        echo "<span style='color:green; font-weight:bold;'>✔ Delivered</span>";
    } else {
        echo "<span style='color:orange;'>Pending</span>";
    }

    echo "</td></tr>";
}
?>
</table>

</div>

</body>
</html>