<?php
	namespace classes;
	/**
	* Database Connection
	*/

	class DbConnect{
		private $server = 'localhost';
		private $dbname = 'jwt';
		private $user = 'shubham';
		private $pass = 'Shubh@11';

		public function connect() {
			try {
				$conn = new \PDO('mysql:host=' .$this->server .';dbname=' . $this->dbname, $this->user, $this->pass);
				$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
				return $conn;
			} catch (\Exception $e) {
				echo "Database Error: " . $e->getMessage();
			}
		}
	}
 ?>