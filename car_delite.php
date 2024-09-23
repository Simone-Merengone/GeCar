<?php
include_once 'common.php';

if( (!isset($_SESSION['id']) || empty($_SESSION['id']))|| ($_SESSION["type"] === "normal") )
    header("Location: ./access_denied.php");

function delete_car_img($img_name) {
    $img_location = '../images/' . $img_name;

    try{

        if (!file_exists($img_location)) {
            throw new Exception("File not found: $img_location");
        }
        if (!unlink($img_location)) {
            throw new Exception("Unable to delete the image: $img_location");
        }
        return true;
      
    } catch (Exception $e) {
      
        log_error(".php/script/delete_car.php delete_car_img(): " . $e->getMessage());

        return false;
    }
}

function delite_car_db() {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['car_id'])) {
        $car_id = $_POST['car_id'];

        try {
            $conn = connection();

            if (!$conn) {
                throw new Exception("Database connection failed");
            }

            // First, delete the car's image
            $car_info = getCarInfo($car_id);
            if (!$car_info) {
                throw new Exception("Car information not found for ID: $car_id");
            }

            $img_name = $car_info['img'];

            if (!delete_car_img($img_name)) {
                return false;
            }

            $query = "DELETE FROM car WHERE id = ?";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Failed to prepare the query: " . $conn->error);
            }

            $stmt->bind_param("i", $car_id);

            if (!$stmt->execute()) {
                throw new Exception("Query execution failed: " . $stmt->error);
            }

            if ($stmt->affected_rows > 0) {
                $stmt->close();
                $conn->close();
                return true;
            } else {
                throw new Exception("No rows affected by the query");
            }

        } catch (Exception $e) {
            log_error(".php/script/delete_car.php delite_car_db(): " . $e->getMessage());

            if (isset($stmt)) {
                $stmt->close();
            }
            if (isset($conn)) {
                $conn->close();
            }
            
            return false;
        }
    }
    return false;
}

function delite_car() {
    if (!delite_car_db())
        return false;
    
    return true;
}

