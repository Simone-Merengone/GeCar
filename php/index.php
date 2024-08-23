<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="../css/index.css">
    <title>HomePage</title>

</head>
<body>

    <?php 
        include_once 'common_layout/navbar.php';
        
        include_once './script/common.php';

        check_user_cookie();

        if((isset($_SESSION['id']) && !empty($_SESSION['id']))){
            $user = getUserInfo($_SESSION['id']);

            if(!empty($user))
                echo "<br><h1>Welcome " . $user['firstname'] . "</h1>";
            else
                echo "<br><h1>Problems to retrive user information</h1>";
        }else
            echo "<br><h1>HomePage</h1>";
    ?>

    <br>

    <p>Find your ideal car with the best offers and promotions available</p>

    <br><br>

    <div>
        <a href="cars.php" class="discover">discover more</a>
    </div>

    <br><br>

    <?php 
        include_once '../html/presentationBoxers.html';
        include_once '../html/common_layout/footer.html';  
    ?>

</body>
</html>