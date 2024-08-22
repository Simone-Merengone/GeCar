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
    <meta name="viewport"  content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../css/car_delite.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Delite/Modify Cars</title>
</head>


<body>
    
    <?php 
        include_once("./common_layout/navbar.php"); 
    ?>

    <br>    

    <h1> Ricerca un'auto da eliminare</h1>

    <br>

    <?php 
        echo'<div class="wrapper">
             <div class="search-container">
                 <!-- Form per la ricerca di base -->
                 <form id="searchForm" action="" method="POST">';

                 include_once("./common_layout/SearchBar.php");

        echo '</form>
                </div>
                    </div>';
    
        include_once './script/modify_delite.php'; 

        choose_op_car();
    ?>

    <?php 
        
        include_once("../html/common_layout/footer.html");
    ?>
    

</body>
</html>
