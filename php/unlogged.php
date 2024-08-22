<?php
if(isset($_SESSION['id']) && !empty($_SESSION['id']))
    header("Location: ./logged.php");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>

    <link rel="stylesheet" href="../css/logged.css">
    <title>Unlogged</title>

</head>


<body>
    
    <?php include_once 'common_layout/navbar.php';  ?>
    
    <div class="container">
        <h1>You aren't login</h1>
        
        <p>You can only access to this page after click on <a href="./login.php"> login</a></p>
    </div>

    <?php include_once '../html/common_layout/footer.html'; ?>
</body>

</html>
