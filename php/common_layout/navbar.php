<?php
    if(session_status() !== PHP_SESSION_ACTIVE) 
        session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../css/navbar.css">
    <title>navbar</title>
</head>

<body>
    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>

        <label class="logo"><b>GeCar</b></label>

        <ul>
            <li><a href="./">Home</a></li>

            <li><a href="./cars.php">Cars</a></li>

        <?php
            if((isset($_SESSION['id']) && !empty($_SESSION['id']))){
                echo '<li><a href="show_profile.php">Show Profile</a></li>';
                echo '<li><a href="./logout.php">Logout</a></li>';
            }else{
                echo '<li><a href="./registration.php">Registration</a></li>
                        <li><a href="./login.php">Login</a></li>';
            }

            if((isset($_SESSION['type']) && !empty($_SESSION['type'])) && ($_SESSION["type"]=="editor" || $_SESSION["type"]=="admin"))
                echo '<li><a href="./admin_area.php">AdminArea</a></li>';

        ?>
        


            
            <li><a href="./AboutUs.php">About us</a></li>
            <li><a href="./Policy.php">Policy</a></li>
        </ul>

    </nav>
</body>

</html>