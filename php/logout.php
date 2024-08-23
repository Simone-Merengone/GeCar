<?php

include_once './script/common.php';

if(session_status() !== PHP_SESSION_ACTIVE) 
    session_start();

    if(isset($_COOKIE['user'])){
        $cookie__hash_value_received = hash("sha256", $_COOKIE['user']);
    
        try {
            $conn = connection();
    
            $stmt = $conn->prepare("UPDATE user SET cookie_hash_value = NULL, cookie_expiration = NULL WHERE cookie_hash_value = ?");
    
            $stmt->bind_param("s", $cookie__hash_value_received);
    
            if (!$stmt->execute()) 
                throw new Exception("\$stmt->execute() failure: " . $stmt->error);
    
            if ($stmt->affected_rows == 0) 
                log_error("./php/logout.php warning: No rows were updated. The provided cookie_hash_value might not exist.");
    
        } catch (Exception $e) {
            log_error("./php/logout.php error: " . $e->getMessage());
        }
    
        setcookie('user', '', time() - 3600);
    }
    



if(isset($_SESSION['id']) && !empty($_SESSION['id'])){
    session_unset(); 
    header("Location: ./index.php");
}