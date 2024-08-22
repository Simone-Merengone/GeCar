<?php

if(session_status() !== PHP_SESSION_ACTIVE) 
    session_start();

if( (!isset($_SESSION['id']) || empty($_SESSION['id']))|| ($_SESSION["type"] === "normal") )
    header("Location: ./access_denied.php"); 
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet" href="./css/HomePage.css">
        <title>Admin Area</title>
    </head>

    <body>
        <?php include_once("./common_layout/navbar.php"); ?>

        <br>
        <br>

        <h1> Administrative Area</h1>

        <?php 
                echo '<br> 

                      <h2> Click on one of the links based on the actions you need to perform:</h2>
                      
                      <br>
                      
                      <h3>to insert a new car click <a href="./car_insert_form.php">here</a> </h3>

                      <br>

                      <h3>to modify/delete a car click <a href="car_modify_delite.php">here</a> </h3>
                      
                      ';
            if($_SESSION["type"] == "admin")
                echo "<br>
                      <h3>to access the user table click <a href='./user_table.php'>here</a> </h3>";

        ?>


        <?php 
            include_once("../html/common_layout/footer.html");
        ?>


    </body>

</html>
