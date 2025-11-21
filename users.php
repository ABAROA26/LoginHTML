<?php 
	#Mostrar errores en php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL); 

	#traer todos los usuarios
	include "./assets/app/UsersController.php";
	$usersC = new UsersController();
	$all_users = $usersC->getAll();

	#Si se está editando un usuario
	$editing_user = null;
	if (isset($_GET['id']) && !empty($_GET['id'])) {
		$editing_user = $usersC->getById($_GET['id']);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Usuarios</title>
</head>
<body>
	<h1>Gestión de Usuarios</h1>

	<?php if(isset($_GET['status']) && $_GET['status'] == 'ok'): ?>
		<div class="success">Operación realizada con éxito</div>
	<?php endif; ?>

	<?php if(isset($_GET['status']) && $_GET['status'] == 'error'): ?>
		<div class="error">Error al realizar la operación</div>
	<?php endif; ?>

	<h2>Lista de Usuarios</h2>
	<table>
		<tr>
			<th>ID</th>
			<th>Nombre</th>
			<th>Correo</th>
			<th>Contraseña</th>
			<th>Acciones</th>
		</tr>

		<?php if(isset($all_users) && count($all_users)): ?>
		<?php foreach($all_users as $user): ?>
		<tr>
			<td><?= $user['id'] ?></td>
			<td><?= $user['nombre'] ?></td>
			<td><?= $user['email'] ?></td>
			<td><?= substr($user['password'], 0, 20) ?>...</td>
			<td>
				<!-- Enlace al formulario de edición en la misma página -->
				<a href="users.php?id=<?= $user['id'] ?>">
					<button>Editar</button>
				</a>
				
				<!-- Formulario para eliminar -->
				<form method="POST" action="./assets/app/UsersController.php">
					<input type="hidden" name="id" value="<?= $user['id'] ?>">
					<input type="hidden" name="action" value="delete_user">
					<button type="submit" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
						Eliminar
					</button>
				</form>
			</td>
		</tr>
		<?php endforeach ?>
		<?php endif ?>
	</table>

	<hr>

	<?php if($editing_user): ?>
		<h2>Editar Usuario</h2>
		<form method="POST" action="./assets/app/UsersController.php">
			<!-- Campo oculto con el ID del usuario -->
			<input type="hidden" name="id" value="<?= $editing_user['id'] ?>">
			
			<div>
				<label>Nombre Completo</label>
				<input type="text" 
					   name="name" 
					   value="<?= htmlspecialchars($editing_user['nombre']) ?>" 
					   placeholder="Nombre completo" 
					   required>
			</div>

			<div>
				<label>Email</label>
				<input type="email" 
					   name="email" 
					   value="<?= htmlspecialchars($editing_user['email']) ?>" 
					   placeholder="Email" 
					   required>
			</div>

			<div>
				<label>Nueva Contraseña (dejar en blanco para no cambiar)</label>
				<input type="password" 
					   name="password" 
					   placeholder="Nueva contraseña (opcional)">
			</div>

			<input type="hidden" name="lastname" value="">

			<button type="submit">Actualizar Usuario</button>
			<a href="users.php"><button type="button">Cancelar</button></a>
			
			<!-- Acción para el controlador -->
			<input type="hidden" name="action" value="update_user">
		</form>
	<?php else: ?>
		<h2>Crear Nuevo Usuario</h2>
		<form method="post" action="./assets/app/UsersController.php">
			<div>
				<label>Nombre Completo</label>
				<input type="text" placeholder="Nombre completo" name="name" required>
			</div>

			<div>
				<label>Email</label>
				<input type="email" placeholder="Email" name="email" required>
			</div>

			<div>
				<label>Password</label>
				<input type="password" placeholder="Password" name="password" required>
			</div>

			<input type="hidden" name="lastname" value="">

			<button type="submit">Guardar datos</button>
			<input type="hidden" name="action" value="create_user">
		</form>
	<?php endif; ?>
</body>
</html>