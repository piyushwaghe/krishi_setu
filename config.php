<?php
$conn = mysqli_connect("localhost", "root", "samadhan", "krushi_setu");
session_start();
if (!$conn) { die("Connection failed: " . mysqli_connect_error()); }

function get_user_name() {
    return $_SESSION['user']['name'] ?? 'User';
}
?>
