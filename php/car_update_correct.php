<?php

if(session_status() !== PHP_SESSION_ACTIVE) 
    session_start();

if((!isset($_SESSION['id']) || empty($_SESSION['id'])) || ($_SESSION["type"] === "normal") )
    header("Location: ./access_denied.php");

if(isset($_SESSION['$update_car_id']))
    $_SESSION['$update_car_id'] = "";
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <link rel="stylesheet" href="../css/registration.css">
    <title>car update-correct</title>
</head>

<body>
    <?php include_once 'common_layout/navbar.php';  ?>
    
    <div class="container">
        <br><h1>Car Update Correct</h1>
        
        <?php
        include_once './script/common.php';
        ?>
        
        <h2>The car that you have selected was correctly updated</h2>
    
    <?php 
        include_once '../html/common_layout/footer.html';
    ?>
</body>

</html>
