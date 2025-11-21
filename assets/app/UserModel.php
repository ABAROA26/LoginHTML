<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 

include "connectionController.php";

class UserModel {

	private $connection;

	public function __construct() {
	    $this->connection = new ConnectionController();
	}

	// Obtener todos los usuarios
	public function get() {	
		$conn = $this->connection->connect();

		$query = "SELECT * FROM usuarios"; 
		$prepared_query = $conn->prepare($query); 
		$prepared_query->execute();

		$results = $prepared_query->get_result();
		$users = $results->fetch_all(MYSQLI_ASSOC);

		return $users;
	}

	// Obtener un usuario por ID
	public function getById($id) {
		$conn = $this->connection->connect();

		$query = "SELECT * FROM usuarios WHERE id = ?";
		$prepared_query = $conn->prepare($query);
		$prepared_query->bind_param('i', $id);
		$prepared_query->execute();

		$results = $prepared_query->get_result();
		$user = $results->fetch_assoc();

		return $user;
	}

	// Crear un nuevo usuario
	public function create($name, $lastname, $email, $password) {
		$conn = $this->connection->connect();

		
		$nombre_completo = trim($name . ' ' . $lastname);

		$query = "INSERT INTO usuarios (nombre, email, password) VALUES (?,?,?)";
		$prepared_query = $conn->prepare($query);
		$prepared_query->bind_param('sss', $nombre_completo, $email, $password);
		
		if ($prepared_query->execute()) {
			return true;
		} else {
			return false;
		}
	}

	// Actualizar un usuario existente
	public function update($name, $lastname, $email, $password, $id) { 
		$conn = $this->connection->connect();

		// Concatenar nombre y apellido
		$nombre_completo = trim($name . ' ' . $lastname);

		// Si la contraseña está vacía, no la actualizamos
		if (empty($password)) {
			$query = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";
			$prepared_query = $conn->prepare($query);
			$prepared_query->bind_param('ssi', $nombre_completo, $email, $id);
		} else {
			$query = "UPDATE usuarios SET nombre = ?, email = ?, password = ? WHERE id = ?";
			$prepared_query = $conn->prepare($query);
			$prepared_query->bind_param('sssi', $nombre_completo, $email, $password, $id);
		}

		if ($prepared_query->execute()) {
			return true;
		} else {
			return false;
		}
	}

	// Eliminar un usuario
	public function delete($id) {
		$conn = $this->connection->connect();
		$query = "DELETE FROM usuarios WHERE id = ?";

		$prepared_query = $conn->prepare($query);
		$prepared_query->bind_param('i', $id);

		if ($prepared_query->execute()) {
			return true;
		} else {
			return false;
		}
	}
}