<?php
include "db_connection.php";

$query = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>BuyGoods - Products</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
            background-color: #f8f8f8;
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
            top: 84px;
            left: 0;
            width: 200px;
            height: calc(100% - 84px);
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
        }

        .sidebar a:hover {
            background-color: #555;
        }

        /* Main content */
        .main-content {
            margin-top: 84px;
            margin-left: 200px;
            padding: 30px;
            min-height: calc(100vh - 84px);
            box-sizing: border-box;
        }

        .main-content h2 {
            text-align: center;
            color: #00796B;
            margin-bottom: 30px;
        }

        /* Product grid */
        .goods-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
        }

        /* Product card */
        .good-item {
            background-color: white;
            padding: 18px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0,0,0,0.12);
            transition: transform 0.2s;
        }

        .good-item:hover {
            transform: translateY(-4px);
        }

        .good-item img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .good-item h3 {
            margin: 8px 0;
            color: #333;
        }

        .price {
            font-size: 18px;
            font-weight: bold;
            color: #00796B;
        }

        .stock {
            font-size: 14px;
            color: #555;
        }

        .buy-button {
            display: inline-block;
            margin-top: 12px;
            padding: 10px 20px;
            background-color: #00796B;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .buy-button:hover {
            background-color: #00695C;
        }

        .out-of-stock {
            color: red;
            font-weight: bold;
            margin-top: 12px;
        }
    </style>
</head>

<body>

<!-- Header -->
<div id="header">
    <?php include "menuhead.php"; ?>
</div>

<!-- Sidebar -->
<div class="sidebar">
    <?php include "side_menu.php"; ?>
</div>

<!-- Main Content -->
<div class="main-content">

    <h2>Available Products</h2>

    <div class="goods-grid">

        <?php
        if (mysqli_num_rows($result) > 0) {

            while ($data = mysqli_fetch_assoc($result)) {
        ?>

                <div class="good-item">

                    <img
                        src="./photo/<?php echo htmlspecialchars($data['image']); ?>"
                        alt="<?php echo htmlspecialchars($data['name']); ?>"
                    >

                    <h3>
                        <?php echo htmlspecialchars($data['name']); ?>
                    </h3>

                    <p class="price">
                        ₹<?php echo number_format($data['price'], 2); ?>
                    </p>

                    <p class="stock">
                        Available:
                        <?php echo (int)$data['quantity']; ?>
                    </p>

                    <p>
                        <?php echo htmlspecialchars($data['state']); ?>
                    </p>

                    <?php if ((int)$data['quantity'] > 0) { ?>

                        <a href="login.php?id2=<?php echo (int)$data['id']; ?>">
    Buy Now
</a>

                    <?php } else { ?>

                        <div class="out-of-stock">
                            Out of Stock
                        </div>

                    <?php } ?>

                </div>

        <?php
            }

        } else {
        ?>

            <p>No products available.</p>

        <?php
        }
        ?>

    </div>

</div>

</body>
</html>