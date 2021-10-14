<?php
	/*
		El siguiente código muestra encabezados sobre quién puede leer este archivo y qué tipo de contenido devolverá.
		En este caso, puede ser leído por cualquier persona.
		Devolverá datos en formato JSON.
	*/
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: access");
	header("Access-Control-Allow-Methods: GET");
	header("Access-Control-Allow-Credentials: true");
	header("Content-Type: application/json; charset=UTF-8");

	include_once '../model/database.php';
	include_once '../controller/product.php';

	$database = new Database();
	$db = $database->getConnection();
	$product = new Product($db);

	// Obtengo el detalle del producto.
	$stmt = $product->getAll();
	$num = $stmt->rowCount();

	// Verifica si se encontraron registros.
	if($num > 0) {
		// Array de productos.
		$products_arr = array();
		$products_arr["records"] = array();

		// Recupera los registros de la tabla Productos.
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			extract($row);

			$product_item = array (
				"ID" => $id,
				"Name" => $name,
				"Price" => $price,
                "Date Created" => $date_created,
                "Status" => $status
			);

			array_push($products_arr["records"], $product_item);
		}

		// Código de respuesta - 200 OK.
		http_response_code(200);

		// Muestra la información de los productos en formato JSON.
		echo json_encode($products_arr);
	
	} else {
		// Muestro mensaje y código de respuesta - 404 Not found.
		echo json_encode (
			array (
				"status" => 404,
				"message" => "No se encuentran productos cargados."
			)
		);
	}
?>