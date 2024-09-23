<?php

include_once 'common.php';

function getCars($manufacturer, $model, $price, $year, $fuel, $gear, $color){
    try {
        $conn = connection();
        if($conn === false){
            echo '<div class="error">Syte problem, try later ...</div>';
            return false;
        }

        $query = "SELECT * FROM car WHERE 1=1";
        $params = [];
        $types = "";

        if (!empty($manufacturer)) {
            $query .= " AND manufacturer = ?";
            $params[] = $manufacturer;
            $types .= "s";
        }

        if (!empty($model)) {
            $query .= " AND model = ?";
            $params[] = $model;
            $types .= "s";
        }

        if (!empty($price)) {
            $query .= " AND price <= ?";
            $params[] = $price;
            $types .= "d";
        }

        if (!empty($year)) {
            $query .= " AND year = ?";
            $params[] = $year;
            $types .= "i";
        }

        if (!empty($fuel)) {
            $query .= " AND fuel = ?";
            $params[] = $fuel;
            $types .= "s";
        }

        if (!empty($gear)) {
            $query .= " AND gear = ?";
            $params[] = $gear;
            $types .= "s";
        }

        if (!empty($color)) {
            $query .= " AND color = ?";
            $params[] = $color;
            $types .= "s";
        }

        $stmt = $conn->prepare($query);
        if (!empty($types)) {
            $stmt->bind_param($types, ...$params);
        }

        if(!$stmt->execute())
            throw new Exception("\$stmt->execute() failure: ");

        $result = $stmt->get_result();
        
        $cars = [];
        while ($car = $result->fetch_assoc()) {
            $cars[] = $car;
        }

        $stmt->close();
        $conn->close();

        return $cars;

    } catch (Exception $e) {
        log_error("./php/script/modify_delite.php getCars() " . $e->getMessage());

        $stmt->close();
        $conn->close();

        return false;
    }
}

function choose_op_car(){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $manufacturer = isset($_POST["manufacturer"]) ? $_POST["manufacturer"] : "";
        $model = isset($_POST["model"]) ? $_POST["model"] : "";
        $price = isset($_POST["price"]) ? $_POST["price"] : null;
        $year = isset($_POST["year"]) ? $_POST["year"] : null;
        $fuel = isset($_POST["fuel"]) ? $_POST["fuel"] : "";
        $gear = isset($_POST["gear"]) ? $_POST["gear"] : "";
        $color = isset($_POST["color"]) ? $_POST["color"] : "";

        $cars = getCars($manufacturer, $model, $price, $year, $fuel, $gear, $color);

        if ($cars === false) {
            return false;
        }

        echo '<div class="container">
            <h1 class="text-center my-4">Car Catalog</h1>
            <div class="row" id="car-catalog">';

        if (empty($cars)) {
            echo '<div class="col-12">
                    <p class="text-center">No cars found with the specified criteria.</p>
                  </div>';
        } else {
            foreach ($cars as $car) {
                echo '<div class="col-md-4">
                        <div class="car-card">
                            <img src="../images/' . $car["img"] . '" alt="' . $car["model"] . '">
                            <h3>' . $car["manufacturer"] . ' ' . $car["model"] . '</h3>
                            <p>Price: $' . $car["price"] . '</p>
                            <p>Year: ' . $car["year"] . '</p>
                            <p>Fuel: ' . $car["fuel"] . '</p>
                            <p>Gear: ' . $car["gear"] . '</p>
                            <p>Color: ' . $car["color"] . '</p>
                            <div class="button-container">
                                <form action="car_modify_form.php" method="post">
                                    <input type="hidden" name="car_id" value="' . $car["id"] . '">
                                    <button type="submit" class="btn-modify">Modify</button>
                                </form>
                                <form action="./car_delite_result.php" method="post" style="margin-top: 10px;">
                                    <input type="hidden" name="car_id" value="' . $car["id"] . '">
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>';
            }
        }

        echo '</div></div>';
    }
}
