<?php
include_once 'common.php';

function ValidationAndCleanUpProf($firstname, $lastname, $email){

    if(validate_input($firstname, "/^[a-zA-Z0-9]+$/", 50, 1) &&
        validate_input($lastname, "/^[a-zA-Z0-9]+$/", 50, 1) &&
        filter_var($email, FILTER_VALIDATE_EMAIL)){
        

        $user = [];
        $user['firstname'] = clean_input($firstname);
        $user['lastname'] = clean_input($lastname);
        $user['email'] = clean_input($email);

        return $user;
    } else {
        return false;
    }
}

function UserUpdate($fname, $lname, $email){
    try {
        $conn = connection();
        if($conn === false){
            return false;
        }

        $stmt = $conn->prepare("UPDATE user SET firstname = ?, lastname = ?, email = ? WHERE id = ?");
        $stmt->bind_param("sssi", $fname, $lname, $email, $_SESSION['id']);
        
        if(!$stmt->execute())
            throw new Exception("\$stmt->execute() error: ");
        
        if ($stmt->affected_rows > 0) {
            $stmt->close();
            $conn->close();

            return true;
        } else {
            $stmt->close();
            $conn->close();
            
            return false;
        }


    } catch (Exception $e) {
        log_error("./php/script/update_profile.php UserUpdate() " . $e->getMessage());
        
        $stmt->close();
        $conn->close();
        
        return false;
    }
}

function UpdateProfile(){

    if(isset($_POST["firstname"]) && isset($_POST["lastname"]) && isset($_POST["email"])){
        
        $user = ValidationAndCleanUpProf($_POST["firstname"], $_POST["lastname"], $_POST["email"]);
        
        if($user){
            if(UserUpdate($user['firstname'], $user['lastname'], $user['email']))
                return true;
            else
                return false;
        }   
        return false;
    }
}
