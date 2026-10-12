<?php
session_start();
include 'config.php';

$msg = "";

// ADD PRODUCT
if(isset($_POST['add'])){
    $name = $_POST['name'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $desc = $_POST['desc'];

    $code = "P" . rand(1000,9999);

    $conn->query("INSERT INTO products(name,price,quantity,description,farmer_id,product_code)
    VALUES('$name',$price,$qty,'$desc',{$_SESSION['id']},'$code')");

    $msg = "Product Added Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Product</title>

<style>
body { font-family: Arial; background:#f4f6f8; margin:0; }

.title {
    text-align:center;
    font-size:36px;
    margin-top:20px;
    color:#1b5e20;
}

.form-box {
    width:420px;
    margin:20px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.2);
}

input, textarea {
    width:100%;
    padding:12px;
    margin:10px 0;
}

button {
    width:100%;
    padding:12px;
    background:#2e7d32;
    color:white;
    border:none;
    cursor:pointer;
}

.msg {
    text-align:center;
    color:green;
}

/* PRODUCT DISPLAY */
.table-box {
    width:80%;
    margin:20px auto;
}

table {
    width:100%;
    background:white;
    border-collapse:collapse;
}

th, td {
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:center;
}
</style>
</head>

<body>

<div class="title">Add Product</div>

<div class="form-box">

<div class="msg"><?php echo $msg; ?></div>

<form method="POST">
<input type="text" name="name" placeholder="Product Name" required>
<input type="number" name="price" placeholder="Price (₹)" required>
<input type="number" name="qty" placeholder="Quantity" required>
<textarea name="desc" placeholder="Description"></textarea>
<button name="add">Add Product</button>
</form>

</div>

<!-- SHOW PRODUCTS -->

<div class="table-box">
<h2>Your Products</h2>

<table>
<tr>
<th>Name</th>
<th>Price</th>
<th>Qty</th>
<th>Code</th>
</tr>

<?php
$res = $conn->query("SELECT * FROM products WHERE farmer_id={$_SESSION['id']}");

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

</div>

</body>
</html>