<?php
	// Headers requeridos.
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

	include_once '../model/database.php';
	include_once '../controller/product.php';

	$database = new Database();
	$db = $database->getConnection();
	$product = new Product($db);

	// Obtener el id del producto a updatear.
    $data = json_decode(file_get_contents("php://input"));
    
    // Seteo el ID del producto a editar.
    $product->id = $data->id;

	// Seteo el estado del producto.
	$product->status = $data->status;

	//Updateo el producto.
	if($product->update()) {
		// Muestro mensaje y código de respuesta - 200 OK.
		echo json_encode (
			array (
				"status" => 200,
				"message" => "El producto ah sido eliminado de manera exitosa."
			)
		);

	} else {
		// Muestro mensaje y código de respuesta - 503 Servicio no disponible.
		echo json_encode (
			array (
				"status" => 503,
				"message" => "Error, no se puede actualizar el producto."
			)
		);
	}
?>