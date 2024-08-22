<?php 
    include_once './script/common.php';

    if(session_status() !== PHP_SESSION_ACTIVE) 
        session_start();

    if((!isset($_SESSION['id']) || empty($_SESSION['id'])))
        header("Location: Unlogged.php");
?>

<!DOCTYPE html>
<html lang="it" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Purchase Issue</title>
</head>

<body>

    <?php 
        include_once("./common_layout/navbar.php");
    ?>

    <h1 class="text-center my-4">We encountered an issue with your purchase</h1>

    <h3 class="text-center my-4">Please try again later or contact our support for assistance</h3>

    <p>If you have any questions or need further assistance, please contact our support team:</p>

    <p>Email: <a href="mailto:gecar@genova.it">gecar@genova.it</a></p>
    <p>Phone: +39 010 1234567</p>

    <?php include_once("../html/common_layout/footer.html"); ?>
</body>

</html>
