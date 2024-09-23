<?php

include_once 'common.php';

function ValidationAndClean($firstname, $lastname, $email, $pass, $confirm){
    if(validate_input($firstname, "/^[a-zA-Z0-9]+$/", 50, 1) &&
        validate_input($lastname, "/^[a-zA-Z0-9]+$/", 50, 1) &&
        filter_var($email, FILTER_VALIDATE_EMAIL) &&
        validate_input($pass, "/^[a-zA-Z0-9!@#$%^&*()_+=]*$/", 50, 7) &&
        validate_input($confirm, "/^[a-zA-Z0-9!@#$%^&*()_+=]*$/", 50, 7)){


        $user = [];
        $user['firstname'] = clean_input($firstname);
        $user['lastname'] = clean_input($lastname);
        $user['email'] = clean_input($email);
        $user['pass'] = clean_input($pass);
        $user['confirm'] = clean_input($confirm);

        return $user;
    } else {
        return false;
    }
}

function insert($fname, $lname, $email, $hash){

    try {
        $conn = connection();
        if($conn === false){
            return false;
        }

        $stmt = $conn->prepare("INSERT INTO user(firstname, lastname, email, pass) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fname, $lname, $email, $hash);
        
        if(!$stmt->execute())
            throw new Exception("\$stmt->execute() error: ");
        
        $stmt->close();
        $conn->close();
        return true;
    } catch (Exception $e) {
        log_error(".php/script/registration.php " . $e->getMessage());

        if(isset($stmt))
            $stmt->close();

        if(isset($conn))
            $conn->close();
        
        return false;
    }
}

function Registration(){

    $user = ValidationAndClean($_POST["firstname"], $_POST["lastname"], $_POST["email"], $_POST["pass"], $_POST["confirm"]);
    if(!empty($user)){
        if(strcmp($user["pass"], $user["confirm"]) === 0){
            $hash = password_hash($user["pass"], PASSWORD_DEFAULT);
            return insert($user["firstname"], $user["lastname"], $user["email"], $hash);
        }
    }

    return false;     
}