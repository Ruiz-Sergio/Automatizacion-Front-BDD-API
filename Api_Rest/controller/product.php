<?php
	class Product {
		// Propiedades para la conexión a la base de datos y la tabla.
		private $conn;
		private $table_name = "products";

        // Propiedades del producto.
        public $id;
		public $name;
        public $price;
        public $date_created;
        public $status;

		// Constructor de la conexción de la base de datos.
		public function __construct($db) {
			$this->conn = $db;
        }

        // Este método obtiene el Json.
		public function getJson() {
            $data = file_get_contents('../model/pub/products.json');
			$products = json_decode($data, true);
			return $products;
        }

        // Método para insertar productos en la base de datos.
		public function insertProducts($data) {

            // Seteo la hora del servidor.
            @$date_created = date("Y-m-d H:i:s",time());
            $date = new DateTime($date_created, new DateTimeZone('America/Argentina/Buenos_Aires'));
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $timeZone = date_default_timezone_get();
            @$date_created = date("Y-m-d H:i:s",time());

            $query = "INSERT INTO `". $this->table_name ."` (name, price, date_created) VALUES ";
            $cantRegistros = count($data['name']);
            $cont = 1;

            for ($j = 0; $j < $cantRegistros; $j++) {
                $query = $query . "('". $data['name'][$j] ."',". (float)$data['price'][$j] .",'". $date_created ."')";
                if ($cont < $cantRegistros) {
                    $query = $query . ",";
                    $cont++;
                } else {
                    $query = $query . ";";
                    $cont = 0;
                }
            }

            // Preparo la declaración de la consulta.
            $stmt = $this->conn->prepare($query);

            //Ejecuto la query.
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function getAll() {
            // Query que obtiene todos los productos cargados.
			$query = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC";

            // Preparo la declaración de la consulta.
            $stmt = $this->conn->prepare($query);

            // Ejecuto la query.
            $stmt->execute();

            return $stmt;
        }

        // Método que obtiene un producto.
		public function getOne() {
			// La query obtiene un solo registro.
			$query = "SELECT * FROM ". $this->table_name ." WHERE id =:id";

			// Preparo la declaración de la consulta.
            $stmt = $this->conn->prepare($query);
            
			$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

			// Ejecuto la query.
            $stmt->execute();
            
            return $stmt;
		}

        public function update() {
			// Query para actualizar el status de un producto.
			$query = "UPDATE " . $this->table_name . " SET status =:status WHERE id =:id";

			// Preparo la declaración de la consulta.
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(":status", $this->status, PDO::PARAM_INT);

			//Ejecuto la query.
			if($stmt->execute()) {
				return true;
			}

			return false;
		}
    }
?>