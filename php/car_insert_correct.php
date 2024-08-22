<?php

if(session_status() !== PHP_SESSION_ACTIVE) 
    session_start();

if((!isset($_SESSION['id']) || empty($_SESSION['id'])) || ($_SESSION["type"] === "normal") )
    header("Location: ./access_denied.php");


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <link rel="stylesheet" href="../css/registration.css">
    <title>car insert correct</title>
</head>

<body>
    <?php include_once 'common_layout/navbar.php';  ?>
    
    <div class="container">
        <br><h1>Car Insert Correct</h1>
        
        <?php
        include_once './script/common.php';
        ?>
        
        <h2>The car was insert correctly</h2>
    
    <?php 
        include_once '../html/common_layout/footer.html';
    ?>
</body>

</html>
