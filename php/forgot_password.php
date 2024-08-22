<?php 
if(session_status() !== PHP_SESSION_ACTIVE) 
    session_start();

if(!isset($_SESSION['id']) || empty($_SESSION['id']))
    header("Location: Unlogged.php");      
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/forgot_password.css"> 
    <title>Forgot Password</title>
</head>
<body>
    <?php 
        include_once 'common_layout/navbar.php';  
    ?>

    <br>
    <h1>Forgot Password</h1>
    <br>

    <div class="container">
        <form method="post" action="">
            <div class="row">
                <div class="col-25">
                    <label for="email">Email</label>
                </div>
                <div class="col-75">
                    <input type="email" id="email" name="email" placeholder="we will send on it the reset password link">
                </div>
            </div>
            <div class="row">
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>

    <?php 
        include_once './script/send-password-reset.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(passReset())
                header("Location: email_send_correct.php");
            else
                echo "<br> <div class='error'>Try with other credentials</div>";
        }



        include_once '../html/common_layout/footer.html';  
    ?>
</body>
</html>
