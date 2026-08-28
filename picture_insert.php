<?php
error_reporting(E_ALL);

include "db_connection.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['upload'])) {

    $item_name = trim($_POST['item_name']);
    $image_price = (float) $_POST['image_price'];
    $quantity = (int) $_POST['quantity'];
    $state = trim($_POST['state']);

    // Check that an image was selected
    if (!isset($_FILES["uploadfile"]) || $_FILES["uploadfile"]["error"] !== UPLOAD_ERR_OK) {
        $msg = "Please choose an image.";
    } else {

        $filename = basename($_FILES["uploadfile"]["name"]);
        $tempname = $_FILES["uploadfile"]["tmp_name"];

        $folder = "./photo/" . $filename;

        // Insert product using prepared statement
        $stmt = $conn->prepare(
            "INSERT INTO products (name, price, image, quantity, state)
             VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sdsis",
            $item_name,
            $image_price,
            $filename,
            $quantity,
            $state
        );

        if ($stmt->execute()) {

            // Move image after successful database insertion
            if (move_uploaded_file($tempname, $folder)) {

                // Redirect so refreshing does NOT insert again
                header("Location: picture_insert.php?success=1");
                exit();

            } else {
                $msg = "Product was added, but image upload failed.";
            }

        } else {
            $msg = "Failed to add product.";
        }

        $stmt->close();
    }
}

if (isset($_GET['success'])) {
    $msg = "Product added successfully!";
}
?>
<!DOCTYPE html>
<html>

<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        #content {
            width: 50%;
            justify-content: center;
            align-items: center;
            margin: 20px auto;
            border: 1px solid #cbcbcb;
        }

        form {
            width: 50%;
            margin: 20px auto;
        }

        #display-image {
            width: 100%;
            justify-content: center;
            padding: 5px;
            margin: 15px;
        }

        img {
            margin: 5px;
            width: 350px;
            height: 250px;
        }
    </style>
    <title>Image Upload</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>

    <div id="content">
       <form method="POST" action="" enctype="multipart/form-data">

    <div class="form-group">

        <p>
            <label>Product Image</label>
            <input class="form-control"
                   type="file"
                   name="uploadfile"
                   accept="image/*"
                   required>
        </p>

        <p>
            <label>Product Name</label>
            <input class="form-control"
                   type="text"
                   name="item_name"
                   required>
        </p>

        <p>
            <label>Price</label>
            <input class="form-control"
                   type="number"
                   name="image_price"
                   step="0.01"
                   min="0"
                   required>
        </p>

        <p>
            <label>Quantity</label>
            <input class="form-control"
                   type="number"
                   name="quantity"
                   min="0"
                   required>
        </p>

        <p>
            <label>State</label>
            <input class="form-control"
                   type="text"
                   name="state"
                   required>
        </p>

    </div>

    <div class="form-group">
        <button class="btn btn-primary"
                type="submit"
                name="upload">
            ADD PRODUCT
        </button>
    </div>

</form>
    </div>

   <div id="display-image">
    <div class="row">

        <?php
        $query = "SELECT * FROM products ORDER BY id DESC";
        $result = mysqli_query($conn, $query);

        while ($data = mysqli_fetch_assoc($result)) {
        ?>

            <div class="col-md-4">
                <div class="image-card">

                    <img
                        src="./photo/<?php echo htmlspecialchars($data['image']); ?>"
                        alt="<?php echo htmlspecialchars($data['name']); ?>"
                        class="img-fluid"
                    >

                    <div class="image-info">

                        <h5>
                            <?php echo htmlspecialchars($data['name']); ?>
                        </h5>

                        <p>
                            <strong>Price:</strong>
                            $<?php echo number_format($data['price'], 2); ?>
                        </p>

                        <p>
                            <strong>Available:</strong>
                            <?php echo (int)$data['quantity']; ?>
                        </p>

                        <p>
                            <strong>State:</strong>
                            <?php echo htmlspecialchars($data['state']); ?>
                        </p>

                    </div>

                </div>
            </div>

        <?php
        }
        ?>

    </div>
</div>



</body>

</html>
