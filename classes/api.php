<?php 
	namespace classes;

	//require_once('DbConnect.php');
	//require_once('rest.php');

	class Api extends Rest {
		
		public function __construct() {
			parent::__construct();
		}
		public function register(){
			
			$name = $this->validateParameter('name',$this->param['name'],STRING);
			$email = $this->validateParameter('email',$this->param['email'],STRING);
			$phone = $this->validateParameter('phone',$this->param['phone'],STRING);			
			$password = $this->validateParameter('password',$this->param['password'],STRING);			
			
			$this->insert('users',[
				'name'=>$name,
				'email'=>$email,
				'phone'=>$phone,
				'password'=>$password,
				'active'=>1,
			]);
			$this->returnResponse(SUCCESS_RESPONSE, "User successfully registered with us");
	
		}
		public function logIn() {
			$email = $this->validateParameter('email', $this->param['email'], STRING);
			$password = $this->validateParameter('password', $this->param['password'], STRING);
			try {
				$stmt = $this->dbConn->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
				$stmt->bindParam(":email", $email);
				$stmt->bindParam(":password", $password);
				$stmt->execute();
				$user = $stmt->fetch(\PDO::FETCH_ASSOC);
				if(!is_array($user)) {
					$this->throwError(INVALID_USER_PASS, "Email or Password is incorrect.");
				}

				if( $user['active'] == 0 ) {
					$this->throwError(USER_NOT_ACTIVE, "User is not activated. Please contact to admin.");
				}

				$paylod = [
					'iat' => time(),
					'iss' => 'localhost',
					'exp' => time() + (15*60*60),
					'userId' => $user['id']
				];

				$token = \classes\JWT::encode($paylod, SECRETE_KEY);
				
				$data = ['token' => $token];
				$this->returnResponse(SUCCESS_RESPONSE, $data);
			} catch (\Exception $e) {
				$this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
			}
		}

		public function addCustomer() {
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);
			$email = $this->validateParameter('email', $this->param['email'], STRING, false);
			$address = $this->validateParameter('address', $this->param['address'], STRING, false);
			$mobile = $this->validateParameter('mobile', $this->param['mobile'], INTEGER, false);

			$cust = new \classes\Customer;
			$cust->setName($name);
			$cust->setEmail($email);
			$cust->setAddress($address);
			$cust->setMobile($mobile);
			$cust->setCreatedBy($this->userId);
			$cust->setCreatedOn(date('Y-m-d'));

			if(!$cust->insert()) {
				$message = 'Failed to insert.';
			} else {
				$message = "Inserted successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function getCustomer() {
			$customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);

			$cust = new \classes\Customer;
			$cust->setId($customerId);
			$customer = $cust->getCustomerDetailsById();
			if(!is_array($customer)) {
				$this->returnResponse(SUCCESS_RESPONSE, ['message' => 'Customer details not found.']);
			}

			$response['customerId'] 	= $customer['id'];
			$response['cutomerName'] 	= $customer['name'];
			$response['email'] 			= $customer['email'];
			$response['mobile'] 		= $customer['mobile'];
			$response['address'] 		= $customer['address'];
			$response['createdBy'] 		= $customer['created_user'];
			$response['lastUpdatedBy'] 	= $customer['updated_user'];
			$this->returnResponse(SUCCESS_RESPONSE, $response);
		}
		public function getAllCustomers() {
			$cust = new \classes\Customer;
			$customers = $cust->getAllCustomers();
			if(!is_array($customers)) {
				$this->returnResponse(SUCCESS_RESPONSE, ['message' => 'Customer details not found.']);
			}
			$this->returnResponse(SUCCESS_RESPONSE, $customers);
		}

		public function updateCustomer() {
			$customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);
			$name = $this->validateParameter('name', $this->param['name'], STRING, false);
			$address = $this->validateParameter('address', $this->param['address'], STRING, false);
			$mobile = $this->validateParameter('mobile', $this->param['mobile'], INTEGER, false);

			$cust = new \classes\Customer;
			$cust->setId($customerId);
			$cust->setName($name);
			$cust->setAddress($address);
			$cust->setMobile($mobile);
			$cust->setUpdatedBy($this->userId);
			$cust->setUpdatedOn(date('Y-m-d'));

			if(!$cust->update()) {
				$message = 'Failed to update.';
			} else {
				$message = "Updated successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}

		public function deleteCustomer() {
			$customerId = $this->validateParameter('customerId', $this->param['customerId'], INTEGER);

			$cust = new \classes\Customer;
			$cust->setId($customerId);

			if(!$cust->delete()) {
				$message = 'Failed to delete.';
			} else {
				$message = "deleted successfully.";
			}

			$this->returnResponse(SUCCESS_RESPONSE, $message);
		}
	}
	
 ?>