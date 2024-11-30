<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

if (isset($_POST['confirm_password']) && isset($_POST['new_password']) && isset($_POST['password']) && isset($_POST['full_name'])) {
	include "../DB_connection.php";

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$email = validate_input($_POST['email']);
	$password = validate_input($_POST['password']);
	$full_name = validate_input($_POST['full_name']);
	$new_password = validate_input($_POST['new_password']);
	$confirm_password = validate_input($_POST['confirm_password']);
   $id = $_SESSION['id'];

	if (empty($password) || empty($new_password) || empty($confirm_password) ) {
		$em = "Mot de passe est demandé";
	    header("Location: ../edit_profile.php?error=$em");
	    exit();
	}else if (empty($full_name)) {
		$em = "Nom complet est demandé";
	    header("Location: ../edit_profile.php?error=$em");
	    exit();
	}else if (empty($email)) {
		$em = "L'email est demandé";
	    header("Location: ../edit_profile.php?error=$em");
	    exit();
	}else if ($confirm_password != $new_password) {
		$em = "le nouveau mot de passe et sa confirmation sont incorrects";
	    header("Location: ../edit_profile.php?error=$em");
	    exit();
	}else {
    
       include "Model/User.php";

       $user = get_user_by_id($conn, $id);
       if ($user) {
       	 if (password_verify($password, $user['password'])) {

			       $new_password = password_hash($new_password, PASSWORD_DEFAULT);



			       $data = array($full_name, $new_password,$email, $id);
			       update_profile($conn, $data);

			       $em = "Modification enregistrée";
				    header("Location: ../edit_profile.php?success=$em");
				    exit();
			    }else {
			    	$em = "Mot de passe incorrect";
				   header("Location: ../edit_profile.php?error=$em");
				   exit();
			    }
		   }else {
            $em = "Erreur inconnue";
			   header("Location: ../edit_profile.php?error=$em");
			   exit();
		   }

    
	}
}else {
   $em = "Erreur inconnue";
   header("Location: ../edit_profile.php?error=$em");
   exit();
}

}else{ 
   $em = "Veuillez vous connecter d'abord";
   header("Location: ../login.php?error=$em");
   exit();
}