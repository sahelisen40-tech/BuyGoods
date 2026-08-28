<?php
session_start();

include "db_connection.php";

/*
 * Make sure the user is logged in.
 */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/*
 * Get the billing/order ID from the URL.
 */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid order.");
}

$billingId = (int) $_GET['id'];

/*
 * Get only this order.
 */
$stmt = $conn->prepare(
    "SELECT id, client_id, fullname, email, address, city, state,
            payment, date, price, item, quantity
     FROM billing
     WHERE id = ?"
);

$stmt->bind_param("i", $billingId);
$stmt->execute();

$result = $stmt->get_result();

$billing = $result->fetch_assoc();

if (!$billing) {
    die("Order not found.");
}

/*
 * Security check:
 * Make sure this order belongs to the currently
 * logged-in user.
 */
if ((int)$billing['client_id'] !== (int)$_SESSION['user_id']) {
    die("You are not authorized to view this order.");
}

/*
 * Get order information.
 */
$itemName = $billing['item'];
$price = (float)$billing['price'];
$quantity = (int)$billing['quantity'];

$fullname = $billing['fullname'];
$email = $billing['email'];
$address = $billing['address'];
$city = $billing['city'];
$state = $billing['state'];
$payment = $billing['payment'];
$orderDate = $billing['date'];

/*
 * Calculate total.
 */
$subtotal = $quantity * $price;
$total = $subtotal;

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Order Confirmation</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .success {
            text-align: center;
            color: #00796B;
            margin-bottom: 10px;
        }

        .success-icon {
            font-size: 45px;
            color: green;
        }

        .message {
            text-align: center;
            color: #555;
            margin-bottom: 30px;
        }

        h3 {
            color: #00796B;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
            margin-top: 25px;
        }

        .info {
            line-height: 1.8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #00796B;
            color: white;
        }

        .total {
            font-weight: bold;
            font-size: 17px;
        }

        .buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 13px;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
        }

        .continue {
            background-color: #00796B;
            color: white;
        }

        .continue:hover {
            background-color: #00695C;
        }

        .orders {
            background-color: #eeeeee;
            color: #333;
        }

        .orders:hover {
            background-color: #dddddd;
        }

    </style>

</head>

<body>

<div class="container">

    <!-- Success message -->

    <div class="success-icon">
        ✓
    </div>

    <h2 class="success">
        Order Placed Successfully!
    </h2>

    <p class="message">
        Thank you, <?php echo htmlspecialchars($fullname); ?>.
        Your order has been successfully placed.
    </p>


    <!-- Order information -->

    <h3>Order Information</h3>

    <div class="info">

        <p>
            <strong>Order ID:</strong>
            #<?php echo $billingId; ?>
        </p>

        <p>
            <strong>Order Date:</strong>
            <?php echo htmlspecialchars($orderDate); ?>
        </p>

    </div>


    <!-- Customer information -->

    <h3>Billing Information</h3>

    <div class="info">

        <p>
            <strong>Name:</strong>
            <?php echo htmlspecialchars($fullname); ?>
        </p>

        <p>
            <strong>Email:</strong>
            <?php echo htmlspecialchars($email); ?>
        </p>

        <p>
            <strong>Address:</strong>
            <?php
            echo htmlspecialchars(
                $address . ', ' . $city . ', ' . $state
            );
            ?>
        </p>

        <p>
            <strong>Payment Method:</strong>
            <?php echo htmlspecialchars(ucfirst($payment)); ?>
        </p>

    </div>


    <!-- Product information -->

    <h3>Order Details</h3>

    <table>

        <tr>
            <th>Item</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>

        <tr>

            <td>
                <?php echo htmlspecialchars($itemName); ?>
            </td>

            <td>
                <?php echo $quantity; ?>
            </td>

            <td>
                ₹<?php echo number_format($price, 2); ?>
            </td>

            <td>
                ₹<?php echo number_format($subtotal, 2); ?>
            </td>

        </tr>

        <tr>

            <td colspan="3" class="total">
                Total
            </td>

            <td class="total">
                ₹<?php echo number_format($total, 2); ?>
            </td>

        </tr>

    </table>


    <!-- Navigation buttons -->

    <div class="buttons">

        <a href="main_body.php" class="btn continue">
            Continue Shopping
        </a>

        <a href="my_orders.php" class="btn orders">
            View My Orders
        </a>

    </div>

</div>

</body>

</html>