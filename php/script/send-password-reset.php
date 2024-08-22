<?php

function tokenUpdate($token, $expiry){
    
    if(session_status() !== PHP_SESSION_ACTIVE) 
        session_start();

    if(isset($_SESSION['id']) && !empty($_SESSION['id']))
        $id = $_SESSION['id'];
    else
        return false;

    include_once 'common.php';

    try{
        $conn = connection();

        if($conn === false){
            return false;
        }

        
        $sql = "UPDATE user SET reset_token_hash = ?, reset_token_expires_at = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);


        $token_hash = hash("sha256", $token);

        $stmt->bind_param("ssd", $token_hash, $expiry, $id);
        
        if(!$stmt->execute())
            throw new Exception("\$stmt->execute() error: ");

        if($stmt->affected_rows === 0) {
            echo "Nessuna riga aggiornata<br>";
            return false;
        }

        $stmt->close();
        $conn->close();

        return true;

    } catch (Exception $e) {
        log_error("./php/script/send-password-reset.php tokenUpdate() ");
        $stmt->close();
        $conn->close();

        return false;
    }
}

function sendmail($token, $email){ 
    $mail = require_once 'mailer.php';

    $mail->setFrom("GeCarSawProject@gmail.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END

    Click <a href="https://saw21.dibris.unige.it/~S4984409/GeCar/php/reset-password.php?token=$token">here</a> 
    to reset your password.

    END;


    try {
        $mail->send();

        if(!$mail)
            throw new Exception("\$mail->send() error:");

        return $mail;
    } catch (Exception $e) {
        error_log("./php/script/send-password-reset.php sendmail error: " . $e->getMessage());
        return false;
    }
}

function passReset(){
    if(isset($_POST["email"]) && !empty($_POST["email"]))
    {   
        $email = $_POST["email"];

        $token = bin2hex(random_bytes(16));

        $expiry = date("Y-m-d H:i:s", time() + 60 * 7);
   
        if(!tokenUpdate($token, $expiry))
            return false;
        else{
            if(sendmail($token, $email))
                return true;
            else
                return false;
            }
    } else 
        return false;

}

