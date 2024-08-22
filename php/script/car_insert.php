<?php

include_once 'common.php';

function move_img($file_extension, $upload_dir, $file_img_tmp_name){
    $unique_id = uniqid();
    $unique_filename = $unique_id . '.' . $file_extension;
    $upload_file = $upload_dir . '/' . $unique_filename;

    if (move_uploaded_file($file_img_tmp_name, $upload_file)) {
        return $unique_filename;
    } else {
        log_error("./php/script/car_insert.php move_uploaded_file() error impossible move uploaded file");
        return false;
    }
}

function clean_and_validate_data() {
    $regex_string = "/^[a-zA-Z0-9\s\-]+$/";
    $regex_number = "/^[0-9]+(\.[0-9]{1,2})?$/";
    $regex_year = "/^[0-9]{4}$/";
    $regex_hp = "/^[0-9]+$/";
    $regex_description = '/^[\p{L}\p{N}\s.,!?\'"-]{10,200}$/u';

    $car = [];

    // Manufacturer
    if (!validate_input($_POST['manufacturer'], $regex_string, 50, 1)) {
        return false;
    }
    $car['manufacturer'] = clean_input($_POST['manufacturer']);

    // Model
    if (!validate_input($_POST['model'], $regex_string, 50, 1)) {
        return false;
    }
    $car['model'] = clean_input($_POST['model']);

    // Price
    if (!validate_input($_POST['price'], $regex_number, 10, 1)) {
        return false;
    }
    $car['price'] = clean_input($_POST['price']);

    // Year
    $min_year = 1950; 
    $max_year = date('Y');

    // Converti l'input in un numero intero
    $year = (int)$_POST['year'];

    // Controlla che l'anno sia valido e compreso tra il minimo e il massimo
    if (!validate_input($_POST['year'], $regex_year, 4, 4) || $year < $min_year || $year > $max_year) {
        return false;
    }

    $car['year'] = clean_input($_POST['year']);

    // Horsepower
    if (!validate_input($_POST['hp'], $regex_hp, 6, 1)) {
        return false;
    }
    $car['hp'] = clean_input($_POST['hp']);

    // Fuel
    $allowed_fuel = ['gasoline', 'diesel', 'electric', 'hybrid'];
    if (!in_array($_POST['fuel'], $allowed_fuel)) {
        return false;
    }
    $car['fuel'] = clean_input($_POST['fuel']);

    // Gear
    $allowed_gear = ['automatic', 'manual', 'semi-automatic'];
    if (!in_array($_POST['gear'], $allowed_gear)) {
        return false;
    }
    $car['gear'] = clean_input($_POST['gear']);

    // Color
    $allowed_color = ['black', 'grey', 'white', 'red', 'green', 'orange', 'yellow'];
    if (!in_array($_POST['color'], $allowed_color)) {
        return false;
    }
    $car['color'] = clean_input($_POST['color']);

    // Description
    if (!validate_input($_POST['description'], $regex_description, 100, 0)) {
        return false;
    }
    $car['description'] = clean_input($_POST['description']);

    // Image
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            echo "<div class='error'>Image extension not valid</div>";
            return false;
        } else {
            $filename = basename($_FILES['img']['name']);

            if (strlen($filename) > 100 || strlen($filename) < 1) {
                echo "File name not valid, too long<br>";
                return false;
            } else {

                $upload_dir = '../images';
    
                $car['img'] = move_img($file_extension, $upload_dir, $_FILES['img']['tmp_name']);

                if(!$car['img'])
                    return false;
            }
        }
    }

    return $car;
}


function db_insert($car) {
    $conn = connection(); // Assumendo che la funzione `connection()` restituisca una connessione MySQLi

    if ($conn == false)
        return false;

    try {
        $image = isset($car['img']) ? $car['img'] : null;

        $query = "
            INSERT INTO car (
                manufacturer, 
                model, 
                price, 
                year, 
                hp, 
                fuel, 
                gear, 
                color, 
                description, 
                img
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($query);

        if (!$stmt) {
            return false;
        }

        $types = "ssdissssss";
        $params = [
            $car['manufacturer'],
            $car['model'],
            $car['price'],
            $car['year'],
            $car['hp'],
            $car['fuel'],
            $car['gear'],
            $car['color'],
            $car['description'],
            $image
        ];

        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            throw new Exception("\$stmt->execute() failed: " . $stmt->error);
        }

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
        log_error("./php/script/car_insert.php db_insert() " . $e->getMessage());

        if (isset($stmt))
            $stmt->close();

        if (isset($conn))
            $conn->close();

        return false;
    }
}


function car_insert(){

    $car = clean_and_validate_data();
    if ($car != false) {
        if (db_insert($car))
            return true;
        else 
            return false;
    } else{
        echo "<div class='error'>Try with other data</div>";
        return false;
    }    
}
