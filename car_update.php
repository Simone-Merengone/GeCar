<?php
include_once 'common.php';

function delite_old_image($old_img) {
    $old_img_path = '../images/' . $old_img;
    if (file_exists($old_img_path)) {
        if (!unlink($old_img_path)) {
            log_error("./php/script/car_update.php unlink(): error, impossible to delete file");
        } else {
            return '../images/';
        }
    }
    return false;
}

function move_img($file_extension, $upload_dir, $file_img_tmp_name) {
    $unique_id = uniqid();
    $unique_filename = $unique_id . '.' . $file_extension;
    $upload_file = $upload_dir . $unique_filename;
    if (move_uploaded_file($file_img_tmp_name, $upload_file)) {
        return $unique_filename;
    } else {
        log_error("./php/script/car_update.php move_uploaded_file(): error moving file");
        return false;
    }
}

function clean_and_validate_data() {
    $regex_string = "/^[a-zA-Z0-9\s\-]+$/";
    $regex_number = "/^[0-9]+(\.[0-9]{1,2})?$/";
    $regex_year = "/^[0-9]{4}$/";
    $regex_hp = "/^[0-9]+$/";
    $regex_description = '/[a-zA-Z0-9,!.]/';

    $car = [];
    

    // ID
    if (!isset($_POST['car_id']) || empty($_POST['car_id'])) {
        echo "<div class='error'>Car ID is missing or empty</div>";
    } else {
        $car['id'] = clean_input($_POST['car_id']);
    }

    // Manufacturer
    if (isset($_POST['manufacturer']) && validate_input($_POST['manufacturer'], $regex_string, 50, 1)) {
        $car['manufacturer'] = clean_input($_POST['manufacturer']);
    } else {
        echo "<div class='error'>Manufacturer is invalid or empty. Must be between 1 and 50 characters</div>";
    }

    // Model
    if (isset($_POST['model']) && validate_input($_POST['model'], $regex_string, 50, 1)) {
        $car['model'] = clean_input($_POST['model']);
    } else {
        echo "<div class='error'>Model is invalid or empty. Must be between 1 and 50 characters</div>";
    }

    // Price
    if (isset($_POST['price']) && validate_input($_POST['price'], $regex_number, 10, 1)) {
        $car['price'] = clean_input($_POST['price']);
    } else {
        echo "<div class='error'>Price is invalid or empty. Must be a valid number</div>";
    }

    // Year
    $min_year = 1950; 
    $max_year = date('Y');
    $year = (int)$_POST['year'];

    if (isset($_POST['year']) && validate_input($_POST['year'], $regex_year, 4, 4) && $year >= $min_year && $year <= $max_year) {
        $car['year'] = clean_input($_POST['year']);
    } else {
        echo "<div class='error'>Year is invalid. Must be a 4-digit year between $min_year and $max_year</div>";
    }

    // Horsepower
    if (isset($_POST['hp']) && validate_input($_POST['hp'], $regex_hp, 4, 1)) {
        $car['hp'] = clean_input($_POST['hp']);
    } else {
        echo "<div class='error'>Horsepower is invalid or empty. Must be a valid number</div>";
    }

    // Fuel
    $allowed_fuel = ['gasoline', 'diesel', 'electric', 'hybrid'];
    if (isset($_POST['fuel']) && in_array($_POST['fuel'], $allowed_fuel)) {
        $car['fuel'] = clean_input($_POST['fuel']);
    } else {
        echo "<div class='error'>Fuel type is invalid. Must be one of: gasoline, diesel, electric, hybrid</div>";
    }

    // Gear
    $allowed_gear = ['automatic', 'manual', 'semi-automatic'];
    if (isset($_POST['gear']) && in_array($_POST['gear'], $allowed_gear)) {
        $car['gear'] = clean_input($_POST['gear']);
    } else {
        echo "<div class='error'>Gear type is invalid. Must be one of: automatic, manual, semi-automatic</div>";
    }

    // Color
    $allowed_color = ['black', 'grey', 'white', 'red', 'green', 'orange', 'yellow'];
    if (isset($_POST['color']) && in_array($_POST['color'], $allowed_color)) {
        $car['color'] = clean_input($_POST['color']);
    } else {
        echo "<div class='error'>Color is invalid. Must be one of: black, grey, white, red, green, orange, yellow</div>";
    }

    // Description
    if (isset($_POST['description']) && validate_input($_POST['description'], $regex_description, 100, 0)) {
        $car['description'] = clean_input($_POST['description']);
    } else {
        echo "<div class='error'>Description is invalid or empty. Must be up to 100 characters long</div>";
    }

    // Image
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_extension = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            echo "<div class='error'>Invalid image format. Allowed formats are: jpg, jpeg, png</div>";
        } else {
            $filename = basename($_FILES['img']['name']);

            if (strlen($filename) > 100 || strlen($filename) < 1) {
                echo "<div class='error'>Image filename is too long or empty. Must be less than 100 characters</div>";
            } else {
                $upload_dir = delite_old_image(getCarInfo($car['id'])['img']);
                if (!$upload_dir) {
                    echo "<div class='error'>Unable to delete old image</div>";
                } else {
                    $car['img'] = move_img($file_extension, $upload_dir, $_FILES['img']['tmp_name']);
                    if (!$car['img']) {
                        echo "<div class='error'>Failed to move uploaded image</div>";
                    }
                }
            }
        }
    }

    return $car;
}

function db_update($car) {
    $conn = connection();

    if ($conn === false) {
        return false;
    }

    try {
        $image = isset($car['img']) ? $car['img'] : null;

        $query = "
            UPDATE car SET 
                manufacturer = ?, 
                model = ?, 
                price = ?, 
                year = ?, 
                hp = ?, 
                fuel = ?, 
                gear = ?, 
                color = ?, 
                description = ?
        ";

        if ($image !== null) {
            $query .= ", img = ?";
        }

        $query .= " WHERE id = ?";

        $stmt = $conn->prepare($query);

        if (!$stmt) {
            return false;
        }

        $types = "ssdisssss";
        $params = [
            $car['manufacturer'],
            $car['model'],
            $car['price'],
            $car['year'],
            $car['hp'],
            $car['fuel'],
            $car['gear'],
            $car['color'],
            $car['description']
        ];

        if ($image !== null) {
            $types .= "s";
            $params[] = $image;
        }

        $types .= "i";
        $params[] = $car['id'];

        $stmt->bind_param($types, ...$params);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
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
        log_error("./php/script/car_update.php db_update(): " . $e->getMessage());

        if (isset($stmt)) {
            $stmt->close();
        }

        if (isset($conn)) {
            $conn->close();
        }

        return false;
    }
}

function car_update() {
    $car = clean_and_validate_data();
    if ($car === false) {
        echo "<div class='error'>Validation failed. Please try with valid input</div>";
        return false;
    }

    if (db_update($car)) {
        return true;
    } else {
        echo "<div class='error'>Change at least one piece of data! No changes were detected</div>";
        return false;
    }
}
