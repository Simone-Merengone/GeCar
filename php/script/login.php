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
        $stmt->bind_param("s", $email);

        if(!$stmt->execute())
            throw new Exception("\$stmt->execute() error: ");

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
        
        
        if(password_verify($pass, $user['pass']))
            return $user;
        else{
            return false;
        }
    } catch (Exception $e) {
        log_error("php/script/Login.php UserExist" . $e->getMessage());
        
        if(isset($stmt))
            $stmt->close();

        if(isset($conn))
            $conn->close();

        return false;
    }
}

function Login(){
        if(isset($_POST["email"]) && isset($_POST["pass"])){
            $user = ValidationAndClean($_POST["email"], $_POST["pass"]);

            if(!empty($user)){
                $user=UserExist($user["email"], $user["pass"]);
                if($user){
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