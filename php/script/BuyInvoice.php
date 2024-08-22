<?php


include_once 'common.php';
include_once('../../external_tool/fpdf186/fpdf.php');


if(session_status() !== PHP_SESSION_ACTIVE) 
    session_start();


define('EURO',chr(128));


function generateUniquePdf($path, $car_id) {

    $current_date = date("Ymd"); 

    $pdf_name = $current_date . ".pdf";
    $file_path = $path . "/" . $pdf_name;

    $counter = 2;
    while (file_exists($file_path)) {
        $pdf_name = $current_date . "_" . $counter . ".pdf";
        $file_path = $path . "/" . $pdf_name;
        $counter++;
    }

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 20);

    $pdf->Cell(0, 10, 'CarGe', 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Via Giuseppe Avezzana, 5, 16134 Genova GE', 0, 1, 'C');
    $pdf->Cell(0, 10, 'cell: +39 010 1234567', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Email: gecar@genova.it', 0, 1, 'C');
    $pdf->Ln(10);

    $user = getUserInfo($_SESSION["id"]);

    // Client details
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Client details', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Firstname: ' . $user['firstname'], 0, 1);
    $pdf->Cell(0, 10, 'Lastname: ' . $user['lastname'], 0, 1);
    $pdf->Cell(0, 10, 'Email: ' . $user['email'], 0, 1);


    $car = getCarInfo($car_id);

    // Car details
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Car details', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Manufacturer: ' . $car['manufacturer'], 0, 1);
    $pdf->Cell(0, 10, 'Model: ' . $car['model'], 0, 1);

    // Invoice details
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Invoice details', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Invoice number: ' . $pdf_name, 0, 1);
    $pdf->Cell(0, 10, 'Date: ' . date("j F Y"), 0, 1);

    // Calculate VAT and totals
    $unitPrice = 200000.00;
    $totalWithoutVat = $unitPrice * 0.78;
    $vat = $unitPrice - $totalWithoutVat;
    $totalWithVat = $unitPrice;

    // Car price details
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 10, 'Description', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Amount', 1, 0, 'C');
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(100, 10, 'Total without VAT', 1, 0);
    $pdf->Cell(40, 10, number_format($totalWithoutVat, 2) . EURO , 1, 0, 'R');
    $pdf->Ln();

    $pdf->Cell(100, 10, 'VAT 22%', 1, 0);
    $pdf->Cell(40, 10, number_format($vat, 2) . EURO , 1, 0, 'R');
    $pdf->Ln();

    $pdf->Cell(100, 10, 'Total with VAT', 1, 0);
    $pdf->Cell(40, 10, number_format($totalWithVat, 2) . EURO , 1, 0, 'R');
    $pdf->Ln();

    // Final note
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(0, 10, 'Thank you for your purchase!', 0, 1, 'C');

    // Save the PDF content to the file
    $pdf_content = $pdf->Output('S');

    if(!file_put_contents($file_path, $pdf_content))
        return false;

    
    return substr($file_path, 4, null);  
}

function createUserFolder($user_id) {
    $user_folder_path = "../../users_invoices/";

    if (!file_exists($user_folder_path))
        mkdir($user_folder_path, 0777, true); // Note: permissions are ignored on Windows. 0777 by default. Reference: https://www.php.net/manual/en/function.mkdir.php

    $user_folder_path .= $user_id;

    if (!file_exists($user_folder_path))
        mkdir($user_folder_path, 0777, true);
    
    return $user_folder_path;
}

function insertInvoice($conn, $car_id) {
    try {

        $stmt = $conn->prepare("INSERT INTO invoice(user_id, date, document) VALUES ( ?, ?, ?)");
        if ($stmt === false) {
            return false;
        }

        $current_date = date("Y-m-d");
        $user_id = $_SESSION['id'];

        $user_folder_path = createUserFolder($user_id);

        if(!$document_path = generateUniquePdf($user_folder_path, $car_id))
            return false;


        $stmt->bind_param("iss", $user_id, $current_date, $document_path);

        if(!$stmt->execute())
            throw new Exception("\$stmt->execute() failure: ");

        if ($stmt->affected_rows <= 0) {
            return false;
        }

        $stmt->close();
        return true;

    } catch (Exception $e) {
        log_error("php/script/BuyInvoice.php insertInvoice() " . $e->getMessage());
        return false;
    }
}

function delite_car_img($car_id){
    $img = getCarInfo($car_id)['img'];
    $img_location = '../../images/' . $img;

    if (!file_exists($img_location)) {
        return false;
    }
    if (!unlink($img_location)) {
        return false;
    }
    return true;
}

function delite_car_db($conn, $car_id) {
    try {

        $stmt = $conn->prepare("DELETE FROM car WHERE id = ?");
        
        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("i", $car_id);

        if (!$stmt->execute()) {
            throw new Exception("delite_car_db \$stmt->execute() failure: ");
        }

        if ($stmt->affected_rows <= 0) {
            return false;
        }

        $stmt->close();
        return true;

        } catch (Exception $e) {
            log_error("php/script/BuyInvoice.php delite_car_db() " . $e->getMessage());

            return false;
        }
}

function BuyRegistration($car_id) {
    $conn = connection();

    if($conn === false){
        return false;
    }
 
    $conn->begin_transaction();

    if(!insertInvoice($conn, $car_id)) {
        return false;
    }

    if(!delite_car_img($car_id)) {
        return false;
    }

    if(!delite_car_db($conn, $car_id)) {
        return false;
    }

    $conn->commit();
    $conn->close();
    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["car_id"])) {
        if (BuyRegistration($_POST["car_id"])) {
            header("Location: ../thanks_buy.php");
        } else {
            header("Location: ../problems_buy.php");
        }
    }
}
