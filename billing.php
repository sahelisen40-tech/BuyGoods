<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "db_connection.php";

/* Logged-in user */
$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];

/* Product selected from main_body.php */
$productId = $_GET['id2'] ?? '';

if (empty($productId) || !is_numeric($productId)) {
    die("No product was selected.");
}

$productId = (int)$productId;

/* Get selected product from products table */
$stmt = $conn->prepare(
    "SELECT id, name, price, image, quantity, state
     FROM products
     WHERE id = ?"
);

$stmt->bind_param("i", $productId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Product not found.");
}

$product = $result->fetch_assoc();

$stmt->close();

/* Check stock */
if ((int)$product['quantity'] <= 0) {
    die("Sorry, this product is currently out of stock.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Shop Layout</title>
    <style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    overflow-x: hidden;
}

/* Header */
#header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 2;
    background-color: white;
    text-align: center;
    padding: 15px;
    border-bottom: 1px solid #ccc;
    box-sizing: border-box;
    height: 84px;
}

/* Sidebar */
.sidebar {
    position: fixed;
    top: 60px;
    left: 0;
    width: 200px;
    height: calc(100% - 60px);
    background-color: gray;
    padding: 10px;
    box-sizing: border-box;
    overflow-y: auto;
}

.sidebar a {
    display: block;
    color: white;
    padding: 8px;
    text-decoration: none;
    white-space: normal;
    word-break: break-word;
}

.sidebar a:hover {
    background-color: #555;
}

/* Main content */
.main-content {
    margin-top: 80px;
    margin-left: 200px;
    padding: 20px;
    background-color: #f8f8f8;
    min-height: calc(100vh - 60px);
    box-sizing: border-box;
    border: 0.5px solid #F0F8FF;
}

/* Billing form */
.billing-form {
    background: #fff;
    padding: 25px;
    border-radius: 8px;
    max-width: 500px;
    margin: auto;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.billing-form h2 {
    text-align: center;
    color: #00796B;
    margin-bottom: 20px;
}

.billing-form label {
    display: block;
    font-weight: bold;
    margin-top: 12px;
}

.billing-form input,
.billing-form select {
    width: 100%;
    padding: 10px;
    margin-top: 6px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
}

.billing-form button {
    margin-top: 20px;
    width: 100%;
    padding: 12px;
    background: #00796B;
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
}

.billing-form button:hover {
    background: #00695C;
}

/* Styling for the image and item details */
.good-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 20px;
    text-align: center;
    padding: 10px;
}

.good-item img {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 10px;
}

.good-item p {
    margin: 5px 0;
    font-size: 14px;
}

.good-item button {
    background-color: #00796B;
    padding: 10px;
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 15px;
    display: block;
}

.good-item button:hover {
    background-color: #00695C;
}
    </style>
</head>
<body>

<div id="header">
    <?php include "menuhead.php"; ?>
</div>

<div class="sidebar">
    <?php include "side_menu.php"; ?>
</div>

<div class="main-content">

    <div class="billing-form">

        <h2>Billing Information</h2>

        <form action="billing_insert.php" method="POST">

            <!-- Customer Information -->

            <label for="fullname">Full Name</label>
            <input
                type="text"
                id="fullname"
                name="fullname"
                value="<?php echo htmlspecialchars($username); ?>"
                required
            >

            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?php echo htmlspecialchars($email); ?>"
                readonly
                required
            >

            <label for="address">Address</label>
            <input
                type="text"
                id="address"
                name="address"
                required
            >

            <label for="city">City</label>
            <input
                type="text"
                id="city"
                name="city"
                required
            >

            <label for="state">State</label>
            <input
                type="text"
                id="state"
                name="state"
                required
            >

            <!-- Payment -->

            <label for="payment">Payment Method</label>

            <select id="payment" name="payment" required>
                <option value="credit">Credit Card</option>
                <option value="debit">Debit Card</option>
                <option value="paypal">PayPal</option>
            </select>


            <!-- Selected Product -->

            <div class="good-item">

                <img
                    src="./photo/<?php echo htmlspecialchars($product['image']); ?>"
                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                >

                <p>
                    <strong>Item name:</strong>
                    <?php echo htmlspecialchars($product['name']); ?>
                </p>

                <p>
                    <strong>Price:</strong>
                    $<?php echo number_format($product['price'], 2); ?>
                </p>

                <p>
                    <strong>Available:</strong>
                    <?php echo (int)$product['quantity']; ?>
                </p>
               <label for="quantity">Quantity</label>

               <input
               type="number"
               id="quantity"
               name="quantity"
               value="1"
               min="1"
               max="<?php echo (int)$product['quantity']; ?>"required>

            </div>


            <!-- Product information -->

            <input
                type="hidden"
                name="product_id"
                value="<?php echo (int)$product['id']; ?>"
            >

            


            <!-- Submit -->

            <button type="submit">
                Submit Payment
            </button>

        </form>

    </div>

</div>
</body>
</html>
