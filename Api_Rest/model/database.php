<?php
	class Database {
		// Credenciales de la Base de Datos.
		private $host = "localhost";
		private $db_name = "api-rest";
		private $username = "root";
		private $password = "";
		public $conn;

		// Método para obtener la conexión de la base de datos.
		public function getConnection() {
			$this->conn = null;

			try {
				$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
				$this->conn->exec("set names utf8");
			}
			catch(PDOException $exception) {
				echo "Error de Conexión: " . $exception->getMessage();
			}

			return $this->conn;
		}
	}
?>