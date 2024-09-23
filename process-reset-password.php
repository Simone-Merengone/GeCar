<?php 
include_once 'common.php';

function valid_token(){
    if(isset($_POST["token"]) && !empty($_GET["token"])){
        $token = $_POST["token"];
    
        $token_hash = hash("sha256", $token);

        try{
                if(!$conn = connection())
                    return false;

                $sql = "SELECT reset_token_hash, reset_token_expires_at FROM user WHERE reset_token_hash = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("d", $token_hash);

                if(!$stmt->execute())
                    throw new Exception("\$stmt->execute() error:");

                $result = $stmt->get_result();

                $user = $result->fetch_assoc(); 
                $stmt->close();
                $conn->close();


                if (empty($user))
                    return false;
                else if(strtotime($user["reset_token_expires_at"]) <= time() || strcmp($token_hash, $user["reset_token_hash"]) != 0){
                    return false;
                }else
                    return true;
            }catch(Exception $e){
                log_error("./script/process-reset-password.php valid_token() " . $e->getMessage());
                return false;
            }
        
    }else
        return false;
}
function change_pass(){
    if(isset($_POST["password"]) && isset($_POST["confirm"])){
        if(validate_input($_POST["password"], "/^[a-zA-Z0-9!@#$%^&*()_+=]*$/", 50, 7) &&
           validate_input($_POST["confirm"], "/^[a-zA-Z0-9!@#$%^&*()_+=]*$/", 50, 7) &&
           $_POST["password"] === $_POST["confirm"]){
            
            try {
                $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
                $sql = "UPDATE user
                        SET pass = ?,
                            reset_token_hash = NULL,
                            reset_token_expires_at = NULL
                        WHERE id = ?";
                $conn = connection();

                if($conn === false){
                   return false;
                }

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sd", $password_hash, $_SESSION['id']);

                if(!$stmt->execute())
                    throw new Exception("\$stmt->execute() error:");
                   
                $stmt->close();
                $conn->close();

                return true;

            } catch (Exception $e) {
                log_error("./script/process-reset-password.php change_pass() " . $e->getMessage());

                $stmt->close();
                $conn->close();

                return false;
            }
        } else {
            return false;
        }
    }
}

function processResetPassword(){
    if(valid_token())
        if(change_pass())
            return true;
    
    return false;
}
