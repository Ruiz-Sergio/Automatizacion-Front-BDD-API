<?php
    header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: access");
	header("Access-Control-Allow-Methods: GET");
	header("Access-Control-Allow-Credentials: true");
	header("Content-Type: application/json; charset=UTF-8");

    include_once '../model/database.php';
    include_once '../controller/product.php';
	include_once '../controller/jsonProcessing.php';

    $database = new Database();
    $db = $database->getConnection();

    $product = new Product($db);
    $jsonProducts = $product->getJson();

    $jsonProcessing = new JsonProcessing();
    $jsonProcessing->dumpJson($jsonProducts);

    $data = $jsonProcessing->getArrayProducts();

    if($product->insertProducts($data)) {
        // Muestro mensaje y código de respuesta - 200 OK.
		echo json_encode (
			array (
				"status" => 200,
				"message" => "Registros insertados correctamente."
			)
		);

    } else {
        // Muestro mensaje y código de respuesta - 400 Solicitud Incorrecta.
		echo json_encode (
			array (
				"status" => 400,
				"message" => "Error, los registros no fueron insertados.."
			)
		);
    }
?>