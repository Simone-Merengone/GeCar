<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport"  content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/cars.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Cars</title>
</head>


<body>
    
    <?php include_once("./common_layout/navbar.php"); ?>

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
 
        include_once './script/SearchCar.php'; 

        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!search_car()){
                echo '<div class="error">Si è verificato un errore durante la ricerca delle auto. Riprova inserendo altri valori.</div>';
                echo '<div class="error">Se il problema persiste contattare gecar@gmail.it</div>';    
            }
        }
       
        include_once("../html/common_layout/footer.html");
    ?>
    

</body>
</html>
