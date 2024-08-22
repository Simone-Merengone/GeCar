<?php 
    include_once './script/common.php';

    if(session_status() !== PHP_SESSION_ACTIVE) 
        session_start();

    //se l'utente prova ad accedere, ma non è loggato
    if((!isset($_SESSION['id']) || empty($_SESSION['id'])))
        header("Location: unlogged.php");
?>

<!DOCTYPE html>
<html lang="it" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Thank you !)</title>
</head>

<body>

    <?php 
        include_once("./common_layout/navbar.php");
    ?>

    <h1 class="text-center my-4">Thank you very much for your purchase</h1>

    <h3 class="text-center my-4">If you need anything else, don't hesitate to contact us !)</h3>

    <p>Here is your purchase invoice:
    
    <?php
        if (isset($_SESSION['id'])) {
            $path = "../users_invoices/" . $_SESSION['id'];
            $current_date = date("Ymd");
            
            $pattern = $path . "/" . $current_date . "*.pdf";
            $files = glob($pattern);
        
            if ($files) {
                $latest_file = '';
                $latest_time = 0;
            
                foreach ($files as $file) {
                    $file_time = filemtime($file);
                    if ($file_time > $latest_time) {
                        $latest_time = $file_time;
                        $latest_file = $file;
                    }
                }
            
                if ($latest_file) {
                    echo "<a href='$latest_file' download>Click here to download your invoice</a> </p>";
                } else {
                    echo "Sorry, but we couldn't find your invoice. Please contact support.</p>";
                }
            } else {
                echo "Sorry, but we couldn't find your invoice. Please contact support.</p>";
            }
        } else {
            echo "Error: User ID not found. Please contact support.</p>";
        }
    ?>


    <?php include_once("../html/common_layout/footer.html"); ?>
</body>

</html>
