<?php 
if(session_status() !== PHP_SESSION_ACTIVE) 
    session_start();

if(!isset($_SESSION['id']) || empty($_SESSION['id']))
    header("Location: Unlogged.php");
        
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="../css/index.css">
    <title>Email send correctly</title>

</head>
<body>

    <?php 
        include_once 'common_layout/navbar.php';   
    ?>

    <br><br>

    <h1>Email send correctly</h1>

    <p>go to your incoming mail and open the link for reset your password</p>

    <br><br>

    <?php
        include_once '../html/common_layout/footer.html';  
    ?>

</body>
</html>