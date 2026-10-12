<?php
session_start();
include 'config.php';

$res = $conn->query("
SELECT o.*, p.name as product
FROM orders o
JOIN products p ON o.product_id = p.id
WHERE o.user_id = {$_SESSION['id']}
");

echo "<h2>My Orders</h2>";

while($row = $res->fetch_assoc()){
    echo "Product: ".$row['product']." | Qty: ".$row['quantity']." | ₹".$row['total_price']." | ".$row['status']."<br><br>";
}
?>