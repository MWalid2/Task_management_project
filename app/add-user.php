<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

if (isset($_POST['user_name']) && isset($_POST['password']) && isset($_POST['full_name']) && $_SESSION['role'] == 'admin') {
	include "../DB_connection.php";

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$user_name = validate_input($_POST['user_name']);
	$password = validate_input($_POST['password']);
	$full_name = validate_input($_POST['full_name']);
	$email = validate_input($_POST['email']);

	if (empty($user_name)) {
		$em = "Username est requis";
	    header("Location: ../add-user.php?error=$em");
	    exit();
	}else if (empty($password)) {
		$em = "Mot de passe est requis";
	    header("Location: ../add-user.php?error=$em");
	    exit();
	}else if (empty($full_name)) {
		$em = "Nom complet est requis";
	    header("Location: ../add-user.php?error=$em");
	    exit();
	}
	else if (empty($email)) {
		$em = "L'email est requis";
	    header("Location: ../add-user.php?error=$em");
	    exit();
	}else {
    
       include "Model/User.php";
       $password = password_hash($password, PASSWORD_DEFAULT);

       $data = array($full_name, $user_name, $password, "employé",$email);
       insert_user($conn, $data);

       $em = "Utilisateur crée avec succès";
	    header("Location: ../add-user.php?success=$em");
	    exit();

    
	}
}else {
   $em = "Erreur inconnue";
   header("Location: ../add-user.php?error=$em");
   exit();
}

}else{ 
   $em = "Veuillez vous connecter d'abord";
   header("Location: ../add-user.php?error=$em");
   exit();
}