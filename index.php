<?php
session_start();
include 'config.php';

$msg = "";

// REGISTER
if(isset($_POST['register'])){
    $name = $_POST['r_name'];
    $email = $_POST['r_email'];
    $phone = $_POST['r_phone'];
    $pass = $_POST['r_password'];
    $role = $_POST['r_role'];
    $address = $_POST['r_address'];

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");

    if($check->num_rows > 0){
        $msg = "User already exists!";
    } else {
        $conn->query("INSERT INTO users(name,email,phone,password,role,address)
        VALUES('$name','$email','$phone','$pass','$role','$address')");
        $msg = "Registration Successful!";
    }
}

// LOGIN
if(isset($_POST['login'])){
    $email = $_POST['l_email'];
    $pass = $_POST['l_password'];
    $role = $_POST['l_role'];

    $res = $conn->query("SELECT * FROM users WHERE email='$email' AND password='$pass' AND role='$role'");

    if($res->num_rows > 0){
        $user = $res->fetch_assoc();
        $_SESSION['id'] = $user['id'];

        if($role == "farmer"){
            header("Location: farmer_dashboard.php");
        } else {
            header("Location: customer_dashboard.php");
        }
        exit();
    } else {
        $msg = "Invalid Login!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Krushi Setu</title>

<style>
body {
    margin:0;
    font-family:Arial;
    background:#f4f6f8;
    display:flex;
    flex-direction:column;
    align-items:center;
}

.title {
    margin-top:40px;
    font-size:48px;
    font-weight:bold;
    color:#1b5e20;
}

.box {
    width:420px;
    background:white;
    margin-top:30px;
    padding:25px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.2);
}

input, select {
    width:100%;
    padding:12px;
    margin:8px 0;
}

button {
    width:100%;
    padding:12px;
    background:#2e7d32;
    color:white;
    border:none;
}

.msg { text-align:center; color:red; }

.toggle { display:flex; }
.toggle button { width:50%; background:#ddd; color:black; }
.active { background:#2e7d32 !important; color:white !important; }
.hidden { display:none; }
</style>
</head>

<body>

<div class="title">Krushi Setu</div>

<div class="box">

<div class="msg"><?php echo $msg; ?></div>

<div class="toggle">
<button id="loginBtn" class="active" onclick="showLogin()">Login</button>
<button id="registerBtn" onclick="showRegister()">Register</button>
</div>

<!-- LOGIN -->
<form method="POST" id="loginForm">
<input type="email" name="l_email" placeholder="Email" required>
<input type="password" name="l_password" placeholder="Password" required>
<select name="l_role">
<option value="farmer">Farmer</option>
<option value="customer">Customer</option>
</select>
<button name="login">Login</button>
</form>

<!-- REGISTER -->
<form method="POST" id="registerForm" class="hidden">
<input type="text" name="r_name" placeholder="Full Name" required>
<input type="email" name="r_email" placeholder="Email" required>
<input type="text" name="r_phone" placeholder="Phone" required>
<input type="text" name="r_address" placeholder="Address" required>
<input type="password" name="r_password" placeholder="Password" required>
<select name="r_role">
<option value="farmer">Farmer</option>
<option value="customer">Customer</option>
</select>
<button name="register">Register</button>
</form>

</div>

<script>
function showRegister(){
    document.getElementById("loginForm").classList.add("hidden");
    document.getElementById("registerForm").classList.remove("hidden");
    document.getElementById("loginBtn").classList.remove("active");
    document.getElementById("registerBtn").classList.add("active");
}
function showLogin(){
    document.getElementById("registerForm").classList.add("hidden");
    document.getElementById("loginForm").classList.remove("hidden");
    document.getElementById("registerBtn").classList.remove("active");
    document.getElementById("loginBtn").classList.add("active");
}
</script>

</body>
</html>