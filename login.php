<?php
session_start();

include 'db_connection.php';

$id = $_GET['id2'] ?? '';

$message = "";
$toastClass = "";
/*
 * If the login form is submitted, the product ID
 * comes from the hidden input named id1.
 */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Get product ID from hidden field
    $id = $_POST['id2'] ?? $_GET['id2'] ?? '';
    $message = "";
    $toastClass = "";

    // Find user by email
    $stmt = $conn->prepare(
        "SELECT id, username, email, password
         FROM register
         WHERE email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {

            // Store logged-in user information
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            /*
             * Login successful.
             *
             * Send the selected product ID to billing.php.
             */
            if (!empty($id) && is_numeric($id)) {

                header(
                    "Location: billing.php?id2=" . urlencode($id)
                );
                exit();

            } else {

                die("Product ID was lost during login.");
            }

        } else {

            $message = "Incorrect email or password.";
            $toastClass = "bg-danger";
        }

    } else {

        $message = "Incorrect email or password.";
        $toastClass = "bg-danger";
    }

    $stmt->close();

} else {

    /*
     * These variables are needed when the login page
     * is displayed for the first time.
     */
    $message = "";
    $toastClass = "";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" 
          content="width=device-width, initial-scale=1.0">
    <link href=
"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="shortcut icon" href=
"https://cdn-icons-png.flaticon.com/512/295/295128.png">
    <script src=
"https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/login.css">
    <title>Login Page</title>
</head>

<body class="bg-light">
    <div class="container p-5 d-flex flex-column align-items-center">
        <?php if ($message): ?>
            <div class="toast align-items-center text-white 
            <?php echo $toastClass; ?> border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo $message; ?>
                    </div>
                    <button type="button" class="btn-close
                    btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        <?php endif;
         ?>
        <form method="post" action="login.php?id2=<?php echo urlencode($id); ?>"    style="height:auto; width:380px; box-shadow: rgba(60, 64, 67, 0.3) 
            0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;">
            <div class="row">
                <i class="fa fa-user-circle-o fa-3x mt-1 mb-2"
          style="text-align: center; color: green;"></i>
                <h5 class="text-center p-4" 
          style="font-weight: 700;">Login Into Your Account</h5>
            </div>
            <div class="col-mb-3">
                <label for="email"><i 
                  class="fa fa-envelope"></i> Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col mb-3 mt-3">
                <label for="password"><i
                  class="fa fa-lock"></i> Password</label>
                <input type="password" name="password" id="password" 
                  class="form-control" required>
            </div>
            <div class="col mb-3 mt-3">
                <button type="submit" 
                  class="btn btn-success bg-success" style="font-weight: 600;">Login</button>
            </div>
            <div class="col mb-2 mt-4">
                <p class="text-center" 
                  style="font-weight: 600; color: navy;">
                  <a href="./register.php"
                        style="text-decoration: none;">Create Account</a> OR <a href="./resetpassword.php"
                        style="text-decoration: none;">Forgot Password</a></p>
						
						
    
                     <input type="hidden" name="id2" value="<?php echo htmlspecialchars($id); ?>">                    
            </div>
                      
        </form>
       </div>
       <script>
        var toastElList = [].slice.call(document.querySelectorAll('.toast'))
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 3000 });
        });
        toastList.forEach(toast => toast.show());
    </script>
</body>

</html> 