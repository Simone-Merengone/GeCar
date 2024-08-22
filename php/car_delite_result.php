<?php

if(session_status() !== PHP_SESSION_ACTIVE) 
    session_start();

if( (!isset($_SESSION['id']) || empty($_SESSION['id'])) || ($_SESSION["type"] === "normal") )
    header("Location: ./access_denied.php"); 
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <title>Delete Car</title>
</head>

<body>
    <?php include_once 'common_layout/navbar.php';  ?>
    
    <div class="container">
        <h1>Delete Car</h1>
        
    <?php
        include_once './script/car_delite.php';

        if(delite_car())
            echo "Car deleted successfully";
        else    
            echo "We encountered some issues deleting the car, please contact the developer.";
    ?>

    <?php include_once '../html/common_layout/footer.html'; ?>
</body>

</html>
