<?php
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

    <link rel="stylesheet" href="../css/buy_car.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Buy Car</title>
</head>

<body>

    <?php
        include_once("./common_layout/navbar.php");

        include_once("./script/common.php");

        $car_id = isset($_POST['car_id']) ? $_POST['car_id'] : '';
        $manufacturer = isset($_POST['manufacturer']) ? $_POST['manufacturer'] : '';
        $model = isset($_POST['model']) ? $_POST['model'] : '';
        $price = isset($_POST['price']) ? $_POST['price'] : '';
        $year = isset($_POST['year']) ? $_POST['year'] : '';
        $fuel = isset($_POST['fuel']) ? $_POST['fuel'] : '';
        $gear = isset($_POST['gear']) ? $_POST['gear'] : '';
        $color = isset($_POST['color']) ? $_POST['color'] : '';
        $img = isset($_POST['img']) ? $_POST['img'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
    ?>

    <div class="container">
        <h1 class="text-center my-4">Buy Confirm</h1>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="car-info">
                        <h2><?php echo $manufacturer . ' ' . $model; ?></h2>
                        <div class="text-center">
                            <img src="../images/<?php echo $img; ?>" class="img-fluid" alt="<?php echo $manufacturer . ' ' . $model; ?>">
                        </div>
                        <p><strong>Price:</strong> $<?php echo $price; ?></p>
                        <p><strong>Year:</strong> <?php echo $year; ?></p>
                        <p><strong>Fuel:</strong> <?php echo $fuel; ?></p>
                        <p><strong>Gear:</strong> <?php echo $gear; ?></p>
                        <p><strong>Color:</strong> <?php echo $color; ?></p>
                        <p><strong>Description:</strong> <?php echo $description; ?></p>

                        <form action="./script/BuyInvoice.php" method="post">
                            <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
                            <input type="hidden" name="price" value="<?php echo $price; ?>">
                            <button type="submit" class="btn btn-primary btn-confirm">Confirm Purchase</button>
                        </form>
                    </div>
                </div>
            </div>

    </div>

    <?php include("../html/common_layout/footer.html"); ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
