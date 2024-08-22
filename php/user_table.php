<?php
if(session_status() !== PHP_SESSION_ACTIVE) 
    session_start();

if( (!isset($_SESSION['id']) || empty($_SESSION['id']))|| ($_SESSION["type"] != "admin") )
    header("Location: ./access_denied.php"); 
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../css/user_table.css">
    <title>User Table</title>
</head>
<body>
    <?php include_once("./common_layout/navbar.php"); ?>
    <br>
    <h1>User Table</h1>
    <table id="userTable" class="display">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Type</th>
                <th>Ban Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                include_once("./script/common.php");
                $users = getUsers();
                if($users !== false) {
                    foreach ($users as $user) {
                        echo "<tr>";
                        echo "<td>{$user['firstname']}</td>";
                        echo "<td>{$user['lastname']}</td>";
                        echo "<td>{$user['email']}</td>";
                        echo "<td>{$user['type']}</td>";

                        if ($user['ban_date'] !== null && new DateTime($user['ban_date']) >= new DateTime()) {
                            echo "<td>{$user['ban_date']}</td>";
                            echo "<td>";
                        } else {
                            echo "<td>Not banned</td>";
                            echo "<td><button class='btn ban-btn' data-id='{$user['id']}'>Ban</button>";
                        }
                        echo "<button class='btn delete-btn' data-id='{$user['id']}'>Delite</button> </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No users found.</td></tr>";
                }
            ?>
        </tbody>
    </table>
    <?php include_once("../html/common_layout/footer.html"); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#userTable').DataTable();

        $('.ban-btn').on('click', function() {

            var id = $(this).data('id');
            var email = $(this).data('email');


            if (confirm('Are you sure you want to ban the user?')) {
                $.ajax({
                    url: './script/ban_user.php',
                    type: 'POST',
                    data: { id: id, email: email },
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.status === 'success') {
                            alert(result.message);
                            location.reload();
                        } else {
                            alert(result.message);
                        }
                    },
                    error: function() {
                        alert('Error communicating with the server. Try again later.');
                    }
                });
            }
        });

        $('.delete-btn').on('click', function() {
            var id = $(this).data('id');
            if (confirm('Are you sure you want to delete the user?')) {
                $.ajax({
                    url: './script/delete_user.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.status === 'success') {
                            alert(result.message);
                            location.reload();
                        } else {
                            alert(result.message);
                        }
                    },
                    error: function() {
                        alert('Error communicating with the server. Try again later.');
                    }
                });
            }
        });
    });
</script>

</body>
</html>
