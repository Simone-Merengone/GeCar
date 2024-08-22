<?php
include_once 'common.php';

function banUser($id) {
    try {
        $conn = connection();
        if ($conn === false) 
            return false;

        $date = new DateTime();
        $date->modify('+2 days');
        $ban_date = $date->format('Y-m-d');

        // Update the ban date for the user
        $stmt = $conn->prepare("UPDATE user SET ban_date = ? WHERE id = ?");
        $stmt->bind_param("si", $ban_date, $id);
        
        if(!$stmt->execute())
            throw new Exception("problem with query in \$stmt->execute()");


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
        log_error(".php/script/ban_user.php banUser() " . $e->getMessage());
        
        $stmt->close();
        $conn->close();

        return false;
    }
}

if (isset($_POST['id'])) {
        if(banUser($_POST['id'])){
            echo json_encode(['status' => 'success', 'message' => 'User banned successfully.']);
        }else{
            echo json_encode(['status' => 'error', 'message' => 'An error occurred. Please try again later.']);        

        }
}
