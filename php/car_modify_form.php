<?php

if(session_status() !== PHP_SESSION_ACTIVE) 
    session_start();

if((!isset($_SESSION['id']) || empty($_SESSION['id'])) || ($_SESSION["type"] === "normal"))
    header("Location: ./access_denied.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['car_id'])) 
    $_SESSION['$update_car_id'] = $_POST['car_id'];

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <link rel="stylesheet" href="../css/registration.css">
    <title>Car Modification Form</title>
</head>

<body>
    <?php include_once 'common_layout/navbar.php';  ?>
    
    <div class="container">
        <br><h1>Car Modification Form</h1>
        
        <?php
        include_once './script/common.php';

            if(empty($car = getCarInfo($_SESSION['$update_car_id'])))
                echo '<h2>Problem retrieving car data, if this problem persists, contact gecar@genova.it</h2>';
        ?>
        
        <div class="container"></div> 
            <form action="" method="POST" enctype="multipart/form-data">

                <input type="hidden" id="id" name="id" value="<?php echo $car['id']; ?>">

                <label for="manufacturer">Manufacturer:</label>
                <input type="text" id="manufacturer" name="manufacturer" value="<?php echo $car['manufacturer']; ?>" required><br><br>

                <label for="model">Model:</label>
                <input type="text" id="model" name="model" value="<?php echo $car['model']; ?>" required><br><br>

                <label for="price">Price:</label>
                <input type="number" step="0.01" id="price" name="price" value="<?php echo $car['price']; ?>" required><br><br>

                <label for="year">Year:</label>
                <input type="number" id="year" name="year" value="<?php echo $car['year']; ?>" required><br><br>

                <label for="hp">Horsepower:</label>
                <input type="number" id="hp" name="hp" value="<?php echo $car['hp']; ?>" required><br><br>

                <label for="fuel">Fuel:</label>
                <select id="fuel" name="fuel" required>
                    <option value="gasoline" <?php if($car['fuel'] == 'gasoline') echo 'selected'; ?>>Gasoline</option>
                    <option value="diesel" <?php if($car['fuel'] == 'diesel') echo 'selected'; ?>>Diesel</option>
                    <option value="electric" <?php if($car['fuel'] == 'electric') echo 'selected'; ?>>Electric</option>
                    <option value="hybrid" <?php if($car['fuel'] == 'hybrid') echo 'selected'; ?>>Hybrid</option>
                </select><br><br>

                <label for="gear">Gearbox:</label>
                <select id="gear" name="gear" required>
                    <option value="automatic" <?php if($car['gear'] == 'automatic') echo 'selected'; ?>>Automatic</option>
                    <option value="manual" <?php if($car['gear'] == 'manual') echo 'selected'; ?>>Manual</option>
                    <option value="semi-automatic" <?php if($car['gear'] == 'semi-automatic') echo 'selected'; ?>>Semi-Automatic</option>
                </select><br><br>

                <label for="color">Color:</label>
                <select id="color" name="color" required>
                    <option value="black" <?php if($car['color'] == 'black') echo 'selected'; ?>>Black</option>
                    <option value="grey" <?php if($car['color'] == 'grey') echo 'selected'; ?>>Grey</option>
                    <option value="white" <?php if($car['color'] == 'white') echo 'selected'; ?>>White</option>
                    <option value="red" <?php if($car['color'] == 'red') echo 'selected'; ?>>Red</option>
                    <option value="green" <?php if($car['color'] == 'green') echo 'selected'; ?>>Green</option>
                    <option value="orange" <?php if($car['color'] == 'orange') echo 'selected'; ?>>Orange</option>
                    <option value="yellow" <?php if($car['color'] == 'yellow') echo 'selected'; ?>>Yellow</option>
                </select><br><br>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="5" cols="50"><?php echo $car['description']; ?></textarea><br><br>

                <label for="img">Choose an image:</label>
                <input type="file" id="img" name="img"><br><br>
                
                <input type="submit" value="Update Car">
            </form>
        </div>

        <br><br><br>
    
    <?php 

        include_once './script/car_update.php';

            if(car_update())
                header("Location: ./car_update_correct.php");

        include_once '../html/common_layout/footer.html';
    ?>
</body>

</html>
