<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: main_body.php");
    exit();
}

/* Logged-in user */
$client_id = $_SESSION['user_id'];

/* Billing information */
$fullname = trim($_POST['fullname']);
$email    = trim($_POST['email']);
$address  = trim($_POST['address']);
$city     = trim($_POST['city']);
$state    = trim($_POST['state']);
$payment  = $_POST['payment'];

/* Product ID */
$product_id = $_POST['product_id'] ?? '';

if (!is_numeric($product_id)) {
    die("Invalid product.");
}

$product_id = (int)$product_id;

$quantity = $_POST['quantity'] ?? '';

if (!is_numeric($quantity) || (int)$quantity < 1) {
    die("Invalid quantity.");
}

$quantity = (int)$quantity;

/* Get actual product from products table */
$stmt = $conn->prepare(
    "SELECT id, name, price, quantity
     FROM products
     WHERE id = ?"
);


$stmt->bind_param("i", $product_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Product not found.");
}

$product = $result->fetch_assoc();

$stmt->close();

/* Check stock */
if ((int)$product['quantity'] < $quantity) {
    die("Sorry, there is not enough stock available.");
}

/* Trusted product information */
$item  = $product['name'];
$price = $product['price'];

$date = date("Y-m-d");

/* Insert billing record */
$stmt = $conn->prepare(
    "INSERT INTO billing
    (client_id, fullname, email, address, city, state, payment, date, price, item, quantity)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)"
);

$stmt->bind_param(
    "issssssdisi",
    $client_id,
    $fullname,
    $email,
    $address,
    $city,
    $state,
    $payment,
    $date,
    $price,
    $item,
    $quantity
);

if ($stmt->execute()) {
    /* Reduce product stock */
    $updateStmt = $conn->prepare(
        "UPDATE products
         SET quantity = quantity - ?
         WHERE id = ?"
    );

    $updateStmt->bind_param(
        "ii",
        $quantity,
        $product_id
    );

    $updateStmt->execute();
    $updateStmt->close();

    $billingId = $stmt->insert_id;

    header("Location: order_summary.php?id=" . $billingId);
    exit();

} else {

    echo "Unable to create billing record: " . $stmt->error;

}


$stmt->close();
$conn->close();
?>