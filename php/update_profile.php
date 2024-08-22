<?php
if(session_status() !== PHP_SESSION_ACTIVE) 
  session_start();

if((!isset($_SESSION['id']) || empty($_SESSION['id'])))
  header("Location: Unlogged.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="../css/registration.css">
    <title>update profile</title>

</head>
<body>

    <?php 
        include_once 'common_layout/navbar.php';  

        include_once './script/update_profile.php';

        $user = getUserInfo($_SESSION["id"]);
    ?>
            

    <br>
    <h1>Update profile's form</h1>
    <br>

    <div class="container">
        <form action="" method="POST">
         <div class="row">
            <div class="col-25">
              <label for="firstname">First Name</label>
            </div>
            <div class="col-75">
              <input type="text" id="firstname" name="firstname" value= <?php echo $user['firstname'];?> >
            </div>
          </div>
         <br>
         <div class="row">
            <div class="col-25">
              <label for="lastname">Last Name</label>
            </div>
            <div class="col-75">
              <input type="text" id="lastname" name="lastname" value= <?php echo $user['lastname'];?> >
            </div>
          </div>
         <br>
         <div class="row">
            <div class="col-25">
              <label for="email">email</label>
            </div>
            <div class="col-75">
              <input type="email" id="email" name="email" value= <?php echo $user['email'];?> >
            </div>
          </div>
         <br>
         <div class="row">
            <input type="submit" value="Submit">
         </div>
    </form>

    </div>

    <?php 
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
          if(UpdateProfile())
            header("Location: ./show_profile.php");
          else
            echo "<br> <div class='error'>Try with other credentials</div>";
        }


        include_once '../html/common_layout/footer.html';  
    ?>

</body>
</html>