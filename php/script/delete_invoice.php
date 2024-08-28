<?php

include_once './common.php';

function db_delite_invoice($path){

    $db_path = substr_replace($path, '', 0, 1);

    try {
        $conn = connection();
        if($conn === false){
            echo '<div class="error">Problemi interni al sito, riprovare più tardi.</div>';
            return false;
        }
    
    
        $stmt = $conn->prepare("DELETE FROM invoice WHERE document = ?");
        $stmt->bind_param("s", $db_path);
        
        if(!$stmt->execute())
            throw new Exception("\$stmt->execute() failure: ");
        
        $stmt->close();
        $conn->close();
        
        return true;
    
    } catch (Exception $e) { 
        log_error("database/script/delete_invoice.php db_delite_invoice() " . $e->getMessage());
    
        $stmt->close();
    
        $conn->close();
        
        return false;
    }
}

function delete_user_dir($path){
    try {
        $directory = dirname($path);
    
        $pdf_files = glob($directory . '/*.pdf');
        $num_pdf_files = count($pdf_files);
    
        if ($num_pdf_files === 0) 
            if (!rmdir($directory))
                throw new Exception("Error removing the directory: $directory");
    }catch (Exception $e) {
        log_error("Errore deleting the file: $path in php/script/delete_file.php rmdir: " . $e->getMessage());
        return false;
    }

     return true;
}


function delite_file($path) {

    $path = '../' . $path;

    if (file_exists($path)) {
        if (unlink($path)) {
            delete_user_dir($path);  
            return true;
        }
    } else {
        log_error("File does not exist: $path in php/script/delete_invoice.php delite_file");
        return false;
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["file"])){
    if(db_delite_invoice($_POST["file"]) && delite_file($_POST["file"]))
        header("Location: ../show_profile.php");
    else
        header("Location: ../delete_invoice_problems.php");
}

