<?php
include_once 'common.php';

function db_delete_user($user_id) {   
    try {
        $conn = connection();
        if ($conn === false)
            return false;

        $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception('Failed to delete user.');
        }

        $stmt->close();
        $conn->close();

        return true;

    } catch (Exception $e) {
        log_error("php/script/delete_user.php db_delete_user() - " . $e->getMessage());

        $stmt->close();
        $conn->close();

        return false;
    }
}

function delete_pdfs($dir) {
    $files = glob($dir . '/*');

    try{
        foreach($files as $file){ 
            if(!is_file($file))
              throw new Exception("$file isn't a file.");
            else
              if(!unlink($file))
                  throw new Exception("Impossible deliting $file");
          }

        return true;
    } catch (Exception $e) {
        log_error("php/script/delete_user.php delete_pdfs() - " . $e->getMessage());
        
        echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
        exit;
    }
}

function folder_delete_user($user_id) {
   $user_folder_path = '../../users_invoices/' . $user_id . "/";

    try { 
        if (!is_dir($user_folder_path)) {
            return true;
        }
   
        if(count(glob($user_folder_path . '/*.pdf')) > 0)
            delete_pdfs($user_folder_path);
        
        if (!rmdir($user_folder_path)) {
            throw new Exception("Unable to delete directory $user_folder_path. Verify it is empty.");
        }

        return true;

    } catch (Exception $e) {
        log_error("php/script/delete_user.php folder_delete_user() - " . $e->getMessage());      
        echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
        exit;
    }
}



if (isset($_POST['id'])) {
    $delite = $_POST['id'];
    
    folder_delete_user($delite);
    
    if (!db_delete_user($delite)){
        echo json_encode(['status' => 'error', 'message' => 'Invalid request.']); 
        exit;  
    }
    
    echo json_encode(['status' => 'success', 'message' => 'User deleted successfully.']);
    exit; 

}