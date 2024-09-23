<?php
include_once 'common.php';

try {
    $conn = connection();

    if ($conn === false) {
        echo json_encode(array('error' => 'Internal issues'));
        exit;
    }

    $query = "SELECT DISTINCT manufacturer FROM car";

    $stmt = $conn->prepare($query);

    if(!$stmt->execute())
        throw new Exception("\$stmt->execute(): ");

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $manufacturers = array();
        while ($row = $result->fetch_assoc()) {
            $manufacturers[] = array(
                'value' => $row['manufacturer'],
                'text' => $row['manufacturer']
            );
        }
        echo json_encode($manufacturers);
    } else {
        echo json_encode(array('error' => 'No car manufacturers found.'));
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    log_error("./php/script/getManufacturers.php error " . $e->getMessage());

    $stmt->close();
    $conn->close();

    echo json_encode(array('error' => 'An error occurred while retrieving car manufacturers.'));
}