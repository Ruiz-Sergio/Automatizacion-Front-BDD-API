<?php
	// Headers requeridos.
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST");
	header("Access-Control-Max-Age: 3600");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

	include_once '../model/database.php';
    include_once '../controller/product.php';

    $data = json_decode(file_get_contents("php://input"));

    $database = new Database();
	$db = $database->getConnection();
	$product = new Product($db);

    // Seteo el ID del producto a buscar.
    $product->id = $data->id;

	// Obtengo el detalle del producto.
    $stmt = $product->getOne();
    $num = $stmt->rowCount();

    if($num > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $product_arr = array (
            	"ID" => $id,
            	"Name" => $name,
            	"Price" => $price,
                "Date Created" => $date_created,
                "Status" => $status
            );
        }

        // Código de Respuesta - 200 OK
		http_response_code(200);

        // Muestra la información del producto en formato JSON.
        echo json_encode($product_arr);

	} else {
        // Muestro mensaje y código de respuesta - 404 Not found.
		echo json_encode (
			array (
				"status" => 404,
				"message" => "El producto no existe."
			)
		);
	}
?>