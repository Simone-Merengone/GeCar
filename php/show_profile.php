<?php
if(session_status() !== PHP_SESSION_ACTIVE) 
  session_start();

if((!isset($_SESSION['id']) || empty($_SESSION['id'])))
    header("Location: Unlogged.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="../css/show_profile.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <title>show profile</title>

</head>
<body>


    <?php 
        include_once 'common_layout/navbar.php';  

        include './script/common.php';

        $user = getUserInfo($_SESSION["id"]);

        echo "<br> <h1>Profile information</h1>";
    
        if(!empty($user)){
            echo "<div>";
            echo "<br><b>Firstname:</b> " . $user['firstname']; 
            echo "<br><b>Lastname:</b> " . $user['lastname']; 
            echo "<br><b>Email:</b> " . $user['email']; 
            echo "<br><b>Password:</b> NOT visible; click <a href='forgot_password.php'>here</a> to change your password <br><br>"; 
            echo "<h2>Click <a href='./update_profile.php'>here</a> to update your information</h2>";
            echo '</div>';
        }else{
            echo "Problems retrieving user data, please try again later, if the problem persists contact gecar@gmail.com";
        }
    ?>

    
    <br>

    <h2>Yours invoices:</h2>

    <table id="invoicesTable" class="display">
        <thead>
            <tr>
                <th>File</th>
                <th>Creation data</th>
                <th>Download</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            if (isset($_SESSION['id'])) {
                $path = "../users_invoices/" . $_SESSION['id'];

                if (is_dir($path)) {
                    $files = glob($path . "/*.pdf");

                    usort($files, function ($a, $b) {
                        return filemtime($b) - filemtime($a);
                    });

                    foreach ($files as $file) {
                        $filename = basename($file);
                        $fileModTime = date('Y-m-d H:i:s', filemtime($file));
                        echo "<tr>";
                        echo "<td>$filename</td>";
                        echo "<td>$fileModTime</td>";
                        echo "<td><a class='download-link' href='$file' download>Download</a></td>";
                        echo "<td>";
                        echo "<form action='./script/delete_invoice.php' method='post'>";
                        echo "<input type='hidden' name='file' value='$file'>";
                        echo "<button type='submit' class='delete-btn'>Delite</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
            }
            ?>
        </tbody>
    </table>

    <br>

    <?php
    include_once("../html/common_layout/footer.html");
    ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#invoicesTable').DataTable({
                "order": [[1, 'desc']],
                "paging": true,
                "searching": true,
                "info": true,
                "language": {
                    "emptyTable": "There aren't invoices at the moment" 
                }
            });
        });
    </script>

</body>

</body>
</html>
