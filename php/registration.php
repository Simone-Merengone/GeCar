<?php
  include_once './script/common.php';

  if(check_user_cookie())
    header("Location: ../php/");
  else if(isset($_SESSION['id']) && !empty($_SESSION['id']))
    header("Location: ./logged.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/registration.css">
    <title>registration</title>
</head>
<body>

  <?php include_once 'common_layout/navbar.php';  ?>

    <br>
    <h1>Registration Form</h1>
    <br>

    <div class="container">
        <form action="" method="POST">
         <div class="row">
            <div class="col-25">
              <label for="firstname">First Name</label>
            </div>
            <div class="col-75">
              <input type="text" id="firstname" name="firstname" placeholder="at least 1 characheter">
            </div>
          </div>
         <br>
         <div class="row">
            <div class="col-25">
              <label for="lastname">Last Name</label>
            </div>
            <div class="col-75">
              <input type="text" id="lastname" name="lastname" placeholder="at least 1 characheter">
            </div>
          </div>
         <br>
         <div class="row">
            <div class="col-25">
              <label for="email">email</label>
            </div>
            <div class="col-75">
              <input type="email" id="email" name="email" placeholder="at least 5 characheters">
            </div>
          </div>
         <br>
         <div class="row">
            <div class="col-25">
              <label for="pass">password</label>
            </div>
            <div class="col-75">
              <input type="password" id="pass" name="pass" placeholder="at least 7 characheters">
            </div>
          </div>
         <br>
         <div class="row">
            <div class="col-25">
              <label for="re-pass">repeat password</label>
            </div>
            <div class="col-75">
              <input type="password" id="confirm" name="confirm" placeholder="at least 7 characheters">
            </div>
          </div>
         <br>
         <div class="row">
            <input type="submit" value="Submit">
          </div>
        </form>

    <?php 

        include_once './script/registration.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
          if(Registration()){
            echo "<br> <div class='correct'>User created :)</div>";

            echo "<script type='text/javascript'>alert('User created :)')</script>";
            


          }else
            echo "<br> <div class='error'>Try with other credentials</div>";
        }

  echo "</div>";

        include_once '../html/common_layout/footer.html';  
    ?>

</body>
</html>