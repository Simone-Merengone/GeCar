<?php
include_once 'common.php';


function ValidationAndClean($email, $pass){
    if( filter_var($email, FILTER_VALIDATE_EMAIL) && validate_input($pass, "/^[a-zA-Z0-9!@#$%^&*()_+=]*$/", 50, 7)){
        $user = [];
        
        $user['email'] = clean_input($email);
        $user['pass'] = clean_input($pass);

        return $user;
    } else {
        return false;
    }
}

function UserExist($email, $pass){
    try {
        $conn = connection();
        if($conn === false){
            return false;
        }

        $stmt = $conn->prepare("SELECT id, type, pass, ban_date FROM user WHERE email=?");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param("s", $email);

        if(!$stmt->execute()) {
            throw new Exception("\$stmt->execute() error: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $user = $result->fetch_assoc(); 

        if($user === null){
            return false;
        }

        if (!is_null($user['ban_date'])) {
            $ban_date = new DateTime($user['ban_date']);
            $current_date = new DateTime();
            if ($ban_date >= $current_date)
                return false;
        }
        
        $stmt->close();
        $conn->close();
        
        if(password_verify($pass, $user['pass'])) {
            return $user;
        } else {
            return false;
        }
    } catch (Exception $e) {
        log_error("php/script/Login.php UserExist " . $e->getMessage());

        if (isset($stmt)) {
            $stmt->close();
        }
        if (isset($conn)) {
            $conn->close();
        }

        return false;
    }
}


function cookie_creation($email) {
    $cookie_name = "user";
    $cookie_value = random_bytes(32);
    $expiration = 86400 * 2; // 86400 = 1 giorno

    setcookie($cookie_name, $cookie_value, time() + $expiration); 

    try {
        $conn = connection();

        if ($conn === false) {
            return false;
        }

        $cookie_expiration = date('Y-m-d H:i:s', time() + $expiration);
        
        $cookie_value_hash = hash("sha256", $cookie_value);

        $stmt = $conn->prepare("UPDATE user SET cookie_hash_value=?, cookie_expiration=? WHERE email=?");
        $stmt->bind_param("sss", $cookie_value_hash, $cookie_expiration, $email);

        if (!$stmt->execute()) {
            throw new Exception("\$stmt->execute() error: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();

        return true;
    } catch (Exception $e) {
        log_error(".php/script/login.php " . $e->getMessage() . " - " . $stmt->error);
        
        if (isset($stmt)) {
            $stmt->close();
        }
        if (isset($conn)) {
            $conn->close();
        }

        return false;
    }
}

function Login(){
        if(isset($_POST["email"]) && isset($_POST["pass"])){
            $user = ValidationAndClean($_POST["email"], $_POST["pass"]);

            if(!empty($user)){
                $user=UserExist($user["email"], $user["pass"]);
                if($user){


                    if(isset($_POST["remember"])){
                        cookie_creation($_POST["email"]);
                    }

                    if(session_status() !== PHP_SESSION_ACTIVE) 
                        session_start();

                    $_SESSION['id'] = $user['id'];
                    $_SESSION['type'] = $user['type'];

                    return true;
                }
                
            }
            
            return false;
        }
}