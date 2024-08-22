<?php 
    include_once './script/common.php';

    if(session_status() !== PHP_SESSION_ACTIVE) 
        session_start();

    if((!isset($_SESSION['id']) || empty($_SESSION['id'])))
        header("Location: unlogged.php");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/index.css">
    <title>Purchase Problems :(</title>
</head>

<body>

    <?php 
        include_once("./common_layout/navbar.php");
    ?>

    <h1>There was a problem with your purchase</h1>


    <p>If you need further assistance, please don't hesitate to contact us at <a href="mailto:gecar@genova.it">gecar@genova.it</a>.</p>

    <?php include_once("../html/common_layout/footer.html"); ?>
</body>

</html>
