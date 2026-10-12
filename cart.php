<?php
session_start();
include 'config.php';

if(!isset($_SESSION['id'])){
    die("Login first");
}

if(isset($_GET['id']) && isset($_GET['qty'])){

    $pid = intval($_GET['id']);
    $qty = intval($_GET['qty']);

    $res = $conn->query("SELECT * FROM products WHERE id=$pid");

    if($res->num_rows == 0){
        die("Product not found");
    }

    $product = $res->fetch_assoc();

    if($qty <= 0){
        die("Invalid quantity");
    }

    if($qty > $product['quantity']){
        die("Not enough stock available");
    }

    $price = $product['price'];
    $total = $price * $qty;

    // INSERT ORDER
    $conn->query("INSERT INTO orders(user_id, product_id, quantity, total_price, status)
    VALUES ({$_SESSION['id']}, $pid, $qty, $total, 'Pending')");

    // UPDATE STOCK
    $conn->query("UPDATE products SET quantity = quantity - $qty WHERE id=$pid");

    echo "<h2 style='text-align:center; color:green;'>Order Placed Successfully!</h2>";
    echo "<div style='text-align:center;'><a href='customer_dashboard.php'>Back</a></div>";
}
?>