<?php
session_start();
include 'config.php';

if(isset($_GET['id'])){
    $oid = $_GET['id'];

    // Update status to Delivered
    $conn->query("UPDATE orders SET status='Delivered' WHERE id=$oid");

    header("Location: customer_dashboard.php");
}
?>