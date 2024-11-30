<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="CSS/style.css">
	<style>
		.login-body {
			background-image: url("img/OCP.png");
			background-repeat: no-repeat; /* Empêche la répétition de l'image */
			background-position: top left; /* Place l'image en haut à gauche */
			background-size: 200px 150px; /* Garde la taille d'origine de l'image */
			background-color: #eaf4ea; /* Ajoute un fond pour le reste de l'écran */
		}

		.form-login {
			background-color: #fff;
			padding: 20px;
			border-radius: 10px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
		}
	</style>
</head>
<body class="login-body">

    <form method="POST" action="app/login.php" class="shadow p-4 rounded form-login">
      	<h3 class="display-4 text-center mb-4">Login</h3>

      	<!-- Gestion des messages d'erreur et succès -->
      	<?php if (isset($_GET['error'])) { ?>
      	  	<div class="alert alert-danger" role="alert">
			  <?php echo stripcslashes($_GET['error']); ?>
			</div>
      	<?php } ?>
      	<?php if (isset($_GET['success'])) { ?>
      	  	<div class="alert alert-success" role="alert">
			  <?php echo stripcslashes($_GET['success']); ?>
			</div>
      	<?php } ?>
  			
        <!-- Champ utilisateur -->
		<div class="mb-3">
		    <label for="username" class="form-label">Username</label>
		    <input type="text" class="form-control" name="user_name" id="username" required>
		</div>

        <!-- Champ mot de passe -->
		<div class="mb-3">
		    <label for="password" class="form-label">Mot de passe</label>
		    <input type="password" class="form-control" name="password" id="password" required>
		</div>

        <!-- Bouton de connexion -->
		<button type="submit" class="btn btn-primary w-100">Login</button>
	</form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
