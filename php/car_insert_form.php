<?php

if(session_status() !== PHP_SESSION_ACTIVE) 
    session_start();

if((!isset($_SESSION['id']) || empty($_SESSION['id'])) || ($_SESSION["type"] === "normal"))
    header("Location: ./access_denied.php");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <link rel="stylesheet" href="../css/registration.css">
    <title>car Insert-Form</title>
</head>

<body>
    <?php include_once 'common_layout/navbar.php';  ?>
    
    <div class="container">
        <br><h1>Car Insert-Form</h1>
        
        <?php
        include_once './script/common.php';
        ?>
        
        <div class="container"></div> 
            <form action="" method="POST" enctype="multipart/form-data">

                <label for="manufacturer">manufacturer:</label>
                <input type="text" id="manufacturer" name="manufacturer" required><br><br>

                <label for="model">model:</label>
                <input type="text" id="model" name="model" required><br><br>

                <label for="price">price:</label>
                <input type="number" step="0.01" id="price" name="price" required><br><br>

                <label for="year">year:</label>
                <input type="number" id="year" name="year" required><br><br>

                <label for="hp">Horse power:</label>
                <input type="number" id="hp" name="hp" required><br><br>

                <label for="fuel">Fuel:</label>
                <select id="fuel" name="fuel" required>
                    <option value="gasoline">gasoline</option>
                    <option value="diesel">diesel</option>
                    <option value="electric">electric</option>
                    <option value="hybrid">hybrid</option>
                </select><br><br>

                <label for="gear">gear:</label>
                <select id="gear" name="gear" required>
                    <option value="automatic">automatic</option>
                    <option value="manual">manual</option>
                    <option value="semi-automatic">semi-automatic</option>
                </select><br><br>

                <label for="color">Color:</label>
                <select id="color" name="color" required>
                    <option value="black">black</option>
                    <option value="grey">grey</option>
                    <option value="white">white</option>
                    <option value="red">red</option>
                    <option value="green">green</option>
                    <option value="orange">orange</option>
                    <option value="yellow">yellow</option>
                </select><br><br>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="5" cols="50"></textarea><br><br>


                <label for="img">Choose an image</label>
                <input type="file" id="img" name="img"><br><br>
                
                
                <input type="submit" value="Insert Car">
            </form>
        </div>

        <br><br><br>
    
    <?php
    
    
        include_once './script/car_insert.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST")
            if(car_insert())
                header("Location: car_insert_correct.php");
    


        include_once '../html/common_layout/footer.html';
    ?>
</body>

</html>
