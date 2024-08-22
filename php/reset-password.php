<?php
include_once './script/common.php';

if(isset($_GET["token"]) && !empty($_GET["token"])){
    $token = $_GET["token"];
    $token_hash = hash("sha256", $token);


    $conn = connection();
    $sql = "SELECT reset_token_hash, reset_token_expires_at FROM user WHERE reset_token_hash = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("d", $token_hash);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc(); 

    $stmt->close();
    $conn->close();
    
    if (empty($user))
        header("Location: invalid_token.php");
    else if(strtotime($user["reset_token_expires_at"]) <= time() || strcmp($token_hash, $user["reset_token_hash"]) != 0)
        header("Location: invalid_token.php");
}else
    header("Location: invalid_token.php");
?>



<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    
</head>
<body>

    <h1>Reset Password</h1>

    <form method="post" action="">

        <input type="hidden" name="token" value= <?php echo $token; ?> >

        <label for="password">New password</label>
        <input type="password" id="password" name="password">

        <label for="confirm">Repeat password</label>
        <input type="password" id="confirm"
               name="confirm">

        <button>Send</button>
    </form>

    <?php

        include_once './script/process-reset-password.php';

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            if(processResetPassword())
                header("Location: pass_change_correct.php");
            else
                echo "try with other credentials</div>";
        }

    ?>

</body>
</html>