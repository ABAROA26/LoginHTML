	<?php 
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL); 

		include "connectionController.php";

	$action = $_POST['action'] ?? '';

	if ($action == "login") {
		$email = $_POST['email'] ?? '';
		$password = $_POST['password'] ?? '';	

		if (!empty($email) && !empty($password)) {
			$auth = new AuthController();
			$auth->login($email, $password);
		} else {
			header('Location: ../../index.html?error=campos_vacios');
			exit();
		}
	}

	if ($action == "register") {
		$nombre = $_POST['nombre'] ?? '';
		$email = $_POST['email'] ?? '';
		$password = $_POST['password'] ?? '';	

		if (!empty($nombre) && !empty($email) && !empty($password)) {
			$auth = new AuthController();
			$auth->register($nombre, $email, $password);
		} else {
			header('Location: ../../register.html?error=campos_vacios');
			exit();
		}
	}

	class AuthController{ 

		private $connection;

		public function __construct() {
			$this->connection = new ConnectionController();
		}

		function login($email, $password)
		{
			$conn = $this->connection->connect();
			
			if (!$conn->connect_error) {

				$query = "SELECT * FROM usuarios WHERE email = ?";

				$prepared_query = $conn->prepare($query);

				$prepared_query->bind_param('s', $email);

				$prepared_query->execute();

				$results = $prepared_query->get_result();
				$users = $results->fetch_all(MYSQLI_ASSOC);

				if (count($users) > 0) {
					if (password_verify($password, $users[0]['password'])) {
						session_start();
						$_SESSION['user_id'] = $users[0]['id'];
						$_SESSION['nombre'] = $users[0]['nombre'];
						$_SESSION['email'] = $users[0]['email'];
						
						header('Location: ../../home.html');
						exit();
					} else {
						header('Location: ../../login.html?error=credenciales_invalidas');
						exit();
					}
				} else {
					header('Location: ../../login.html?error=usuario_no_encontrado');
					exit();
				}

			} else {
				header('Location: ../../login.html?error=conexion');
				exit();
			}
		}

		function register($nombre, $email, $password)
	{
		$conn = $this->connection->connect();
		
		if (!$conn->connect_error) {
			
			$query_check = "SELECT * FROM usuarios WHERE email = ?";
			$stmt_check = $conn->prepare($query_check);
			$stmt_check->bind_param('s', $email);
			$stmt_check->execute();
			$result_check = $stmt_check->get_result();
			
			if ($result_check->num_rows > 0) {
				header('Location: ../../register.html?error=email_existe');
				exit();
			}
			
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			
			$query = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
			$prepared_query = $conn->prepare($query);
			$prepared_query->bind_param('sss', $nombre, $email, $hashed_password);
			
			if ($prepared_query->execute()) {
				header('Location: ../../index.html?success=registro_exitoso');
				exit();
			} else {
				header('Location: ../../register.html?error=registro_fallido');
				exit();
			}
			
		} else {
			header('Location: ../../register.html?error=conexion');
			exit();
		}
	}
	}
	?>