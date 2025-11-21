<?php

include "UserModel.php";

	// Crear usuario
	if (isset($_POST['action']) && $_POST['action'] == "create_user") {
	 	$name = $_POST['name'];
	 	$lastname = (isset($_POST['lastname']) ? $_POST['lastname'] : "");
	 	$email = $_POST['email'];
		$password = $_POST['password'];	

		$user = new UsersController();
		$user->create($name, $lastname, $email, $password);
	}

	// Actualizar usuario
	if (isset($_POST['action']) && $_POST['action'] == "update_user") {
		$id = $_POST['id'];
		$name = $_POST['name'];
		$lastname = (isset($_POST['lastname']) ? $_POST['lastname'] : "");
		$email = $_POST['email'];
		$password = $_POST['password'];

		$user = new UsersController();
		$user->update($id, $name, $lastname, $email, $password);
	}

	// Eliminar usuario
	if (isset($_POST['action']) && $_POST['action'] == "delete_user") {
		$id = $_POST['id'];
		
		$user = new UsersController();
		$user->delete($id);
	}

class UsersController {

	private $User;

	public function __construct() {
	    $this->User = new UserModel();
	}

	// Obtener todos los usuarios
	public function getAll() {
		return $this->User->get();
	}

	// Obtener un usuario por ID
	public function getById($id) {
		return $this->User->getById($id);
	}

	// Crear un nuevo usuario
	public function create($name, $lastname, $email, $password) {
		if ($this->User->create($name, $lastname, $email, $password)) {
			header('Location: /LoginHTML/users.php?status=ok');
		} else {
			header('Location: /LoginHTML/users.php?status=error');
		}
	}

	// Actualizar un usuario existente
	public function update($id, $name, $lastname, $email, $password) {
		// Si la contraseña está vacía, no la actualizamos
		if ($this->User->update($name, $lastname, $email, $password, $id)) {
			header('Location: /LoginHTML/users.php?status=ok');
		} else {
			header('Location: /LoginHTML/users.php?status=error');
		}
	}

	// Eliminar un usuario
	public function delete($id) {
		if ($this->User->delete($id)) {
			header('Location: /LoginHTML/users.php?status=ok');
		} else {
			header('Location: /LoginHTML/users.php?status=error');
		}
	}
}