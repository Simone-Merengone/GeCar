<?php

function log_error($message) {

    $timestamp = date("Y-m-d H:i:s");

    $mess = "[$timestamp] " . $message . "\n";

    if((isset($_SESSION['id']) && !empty($_SESSION['id'])))
        $mess .= " id:" . $_SESSION['id'] . " ";
        
    error_log($mess, 3, '../log/errors.log');
}

function connection() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "carge";

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

        if($user === null){
            return false;
        }
        
        return $user;
    } catch (Exception $e) {
        log_error(".php/script/Common.php getUserInfo():" . $e->getMessage());
        
        if(isset($stmt))
            $stmt->close();

        if(isset($conn))
            $conn->close();

        return false;
    }
}

function getCarInfo($id){
    try {
        $conn = connection();
        if($conn === false){
            return false;
        }

        $stmt = $conn->prepare("SELECT id, manufacturer, model, price, year, hp, fuel, gear, color, description, img FROM car WHERE id=?");
        $stmt->bind_param("d", $id);

        if(!$stmt->execute())
            throw new Exception("\$stmt->execute() failure: ");

        $result = $stmt->get_result();
        $car = $result->fetch_assoc(); 

        if($car === null){
            return false;
        }
        
        return $car;
    } catch (Exception $e) {
        log_error("./php/script/Common.php getCarInfo(): " . $e->getMessage());
        
        if(isset($stmt))
            $stmt->close();

        if(isset($conn))
            $conn->close();

        return false;
    }
}

function getUsers(){
    try {
        $conn = connection();

        if ($conn === false) {
            return false;
        }

        $stmt = $conn->prepare("SELECT id, firstname, lastname, email, type, ban_date FROM user");

        if(!$stmt->execute())
            throw new Exception("\$stmt->execute() failure: ");

        $result = $stmt->get_result();
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        if (empty($users)) {
            return "false";
        }
        
        return $users;
    } catch (Exception $e) {
        log_error(".php/script/common.php getUsers " . $e->getMessage());
        
        if (isset($stmt))
            $stmt->close();
    
        if (isset($conn))
            $conn->close();

        return false;
    }
}