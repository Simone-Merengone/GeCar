<?php
include_once 'common.php';

if (isset($_GET['manufacturer'])) {
    $manufacturer = $_GET['manufacturer'];

    try {
        $conn = connection();
        if ($conn === false) {
            echo json_encode(['error' => 'Database connection error']);
            exit;
        }

        $stmt = $conn->prepare("SELECT DISTINCT model FROM car WHERE manufacturer = ?");
        $stmt->bind_param("s", $manufacturer);

        if(!$stmt->execute())
            throw new Exception("\$stmt->execute() problem: ");

        $result = $stmt->get_result();

        $models = [];
        while ($row = $result->fetch_assoc()) {
            $models[] = [
                'value' => $row['model'],
                'text' => $row['model']
            ];
        }

        echo json_encode($models);

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        log_error(".php/script/getModels.php " . $e->getMessage());
        echo json_encode(['error' => 'An error occurred while fetching models']);
        
        if(isset($stmt)) 
            $stmt->close();

        if(isset($conn))     
            $conn->close();
        }

}
