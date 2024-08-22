<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/login.css">
    <title>login</title>

</head>
<body>
    <?php 
      include_once 'common_layout/navbar.php';  

      //se utente già loggato prova ad accedere
      if(isset($_SESSION['id']) && !empty($_SESSION['id']))
        header("Location: ./logged.php");

    ?>

    <br>


    <div class="dist">
      <h1>login Form</h1>
      <div class="container">
          <form action="" method="POST">

            <div class="row">
              <div class="col-25">
                <label for="email">email</label>
              </div>
              <div class="col-75">
                <input type="email" id="email" name="email" placeholder="your email..">
              </div>
            </div>

            <br>

            <div class="row">
              <div class="col-25">
                <label for="pass">password</label>
              </div>
              <div class="col-75">
                <input type="password" id="pass" name="pass" placeholder="Your password..">
              </div>
            </div>

           <br>
            <div class="row">
              <input type="submit" value="Submit">
            </div>

          </form>
      </div>

      <?php 
        include_once './script/login.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          if(Login()){
            header("Location: index.php");
          }
          else
            echo "<br> <div class='error'>Try with other credentials</div>";
        }
      ?>
      
    </div>   
    <?php
        include_once '../html/common_layout/footer.html';  
    ?>

</body>
</html>