<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function log_error($message) {

    $timestamp = date("Y-m-d H:i:s");

    $mess = "[$timestamp] " . $message . "\n";

    if((isset($_SESSION['id']) && !empty($_SESSION['id'])))
        $mess .= " id:" . $_SESSION['id'] . " ";
        
    error_log($mess, 3, '../log/errors.log');
}

function connection() {
    $servername = "your_servername";
    $username = "your_username";
    $password = "your_password";
    $dbname = "your_dbname";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        log_error("Connection failed: " . $conn->connect_error);
        return false;
    }

    return $conn;
}

function validate_input($data, $regex, $lmax, $lmin) {
    if (empty($data) || !isset($data))
        return false;
    elseif (strlen($data) > $lmax || strlen($data) <$lmin)
        return false;
    elseif (!preg_match($regex, $data)) 
        return false;

    return true;
}

function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function update_cookie($id, $conn) {
    $cookie_value = random_bytes(32);
    $expiration = 86400 * 2;
    setcookie('user', $cookie_value, time() + $expiration);
    $cookie_expiration = date('Y-m-d H:i:s', time() + $expiration); 

    $cookie_hash_value = hash("sha256", $cookie_value);

    try{
        $stmt = $conn->prepare("UPDATE user SET cookie_hash_value=?, cookie_expiration=? WHERE id=?");
        $stmt->bind_param("ssd", $cookie_hash_value, $cookie_expiration, $id);
        
        if(!$stmt->execute())
            throw new Exception("\$stmt->execute() failure: ");

    }catch(Exception $e){
        log_error(".php/script/common.php update_cookie() error: " . $e->getMessage());
    }
}

function check_user_cookie() {
    if (isset($_COOKIE['user']) && !empty($_COOKIE['user'])) {

        $cookie_value_received = $_COOKIE['user'];
        $cookie_hash_value_received = hash("sha256", $cookie_value_received);

        try {
            $conn = connection();

            if ($conn === false) 
                return false;

            $stmt = $conn->prepare("SELECT id, cookie_expiration, type FROM user WHERE cookie_hash_value = ?");

            $stmt->bind_param("s", $cookie_hash_value_received);
            
            if (!$stmt->execute()) 
                throw new Exception("\$stmt->execute() failure: " . $stmt->error);

            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($id, $cookie_expiration, $u_type);
                $stmt->fetch();

             if (strtotime($cookie_expiration) > time()) {
                    update_cookie($id, $conn);

                    $_SESSION['id'] = $id;
                    $_SESSION['type'] = $u_type;

                }
            }else{
                $stmt->close();
                $conn->close();

                return false;
            }

            $stmt->close();
            $conn->close();

            return true;

        } catch (Exception $e) {
            log_error(".php/script/common.php check_user_cookie() error: " . $e->getMessage());
            
            $stmt->close();
            $conn->close();
            
            return false;
        }
    }
}

function getUserInfo($id){
    try {

        $conn = connection();
        if($conn === false){
            return false;
        }

        $stmt = $conn->prepare("SELECT firstname, lastname, email, type, ban_date FROM user WHERE id=?");
        $stmt->bind_param("d", $id);
        
        if(!$stmt->execute())
            throw new Exception("\$stmt->execute() failure: ");
        
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if($user === null)
            return false;
        
        return $user;
    } catch (Exception $e) {
        log_error(".php/script/Common.php getUserInfo():" . $e->getMessage());
        
        $stmt->close();
        $conn->close();

        return false;
    }
}

function getCarInfo($id){
    try {
        $conn = connection();

        if($conn === false)
            return false;
        

        $stmt = $conn->prepare("SELECT id, manufacturer, model, price, year, hp, fuel, gear, color, description, img FROM car WHERE id=?");
        $stmt->bind_param("d", $id);

        if(!$stmt->execute())
            throw new Exception("\$stmt->execute() failure: ");

        $result = $stmt->get_result();
        $car = $result->fetch_assoc(); 

        if($car === null)
            return false;
        
        return $car;
    } catch (Exception $e) {
        log_error("./php/script/Common.php getCarInfo(): " . $e->getMessage());
        
        $stmt->close();
        $conn->close();

        return false;
    }
}

function getUsers(){
    try {
        $conn = connection();

        if ($conn === false)
            return false;

        $stmt = $conn->prepare("SELECT id, firstname, lastname, email, type, ban_date FROM user");

        if(!$stmt->execute())
            throw new Exception("\$stmt->execute() failure: ");

        $result = $stmt->get_result();
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        if (empty($users)) 
            return false;
        
        
        return $users;
    } catch (Exception $e) {
        log_error(".php/script/common.php getUsers " . $e->getMessage());
        
        $stmt->close();
        $conn->close();

        return false;
    }
}