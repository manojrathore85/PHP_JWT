<?php 
	/**
	* Database Connection
	*/
	namespace classes;
	class DbConnect {
		private $server = 'localhost';
		private $dbname = 'jwt';
		private $user = 'root';
		private $pass = '';

		public function connect() {
			try {
				$conn = new PDO('mysql:host=' .$this->server .';dbname=' . $this->dbname, $this->user, $this->pass);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				return $conn;
			} catch (\Exception $e) {
				echo "Database Error: " . $e->getMessage();
			}
		}
		public function  insert($table, $data){

			$column_string = implode("`,`", array_keys($data));
			$column_string = rtrim($column_string, ",");
			$value_string = implode("','", array_values($data));
			$value_string = rtrim($value_string, ",");

			$sql = "insert into `$table` (`$column_string`) values ('$value_string')"; 
			$stmt = $this->connect->prepare($sql);
			$stmt= $pdo->connect()->;
			$stmt->execute($data);
			return $stmt;
		} 
	}
 ?>