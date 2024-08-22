<?php 
    include_once './script/common.php';

    if(session_status() !== PHP_SESSION_ACTIVE) 
        session_start();

    if((!isset($_SESSION['id']) || empty($_SESSION['id'])))
        header("Location: Unlogged.php");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Delite Invoice Issue</title>
</head>

<body>

    <?php 
        include_once("./common_layout/navbar.php");
    ?>

    <div class="container mt-5">
        <h1>Issue with Your Delite Invoice</h1>

        <p>We encountered a problem processing your Delite invoice. We apologize for the inconvenience.</p>

        <h3>What to Do Next:</h3>
        <ul>
            <li>Ensure that all the invoice details are correct.</li>
            <li>Try accessing your invoice again after a few minutes.</li>
            <li>If the issue persists, please reach out to our support team.</li>
        </ul>

        <p>For assistance, please contact our support team:</p>

        <p>
            Email: <a href="mailto:gecar@gmail.com">gecar@gmail.com</a><br>
            Phone: +39 010 1234567
        </p>
    </div>

    <?php include_once("../html/common_layout/footer.html"); ?>
</body>

</html>
