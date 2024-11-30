<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['assigned_to']) && $_SESSION['role'] == 'admin' && isset($_POST['due_date'])) {
	include "../DB_connection.php";

    function validate_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}

	$title = validate_input($_POST['title']);
	$description = validate_input($_POST['description']);
	$assigned_to = $_POST['assigned_to']; // No validation, handle as an array
	$id = validate_input($_POST['id']);
	$due_date = validate_input($_POST['due_date']);

	if (empty($title)) {
		$em = "Title is required";
	    header("Location: ../edit-task.php?error=$em&id=$id");
	    exit();
	} else if (empty($description)) {
		$em = "Description is required";
	    header("Location: ../edit-task.php?error=$em&id=$id");
	    exit();
	} else if (empty($assigned_to)) {
		$em = "Select at least one user";
	    header("Location: ../edit-task.php?error=$em&id=$id");
	    exit();
	} else {
		include "Model/Task.php";

		// Convert assigned_to array to JSON or another format for storage
		$assigned_to_json = json_encode($assigned_to);

		$data = array($title, $description, $assigned_to_json, $due_date, $id);
		update_task($conn, $data);

		$em = "Tache mise à jour avec succès";
	    header("Location: ../edit-task.php?success=$em&id=$id");
	    exit();
	}
} else {
   $em = "Erreur inconnue";
   header("Location: ../edit-task.php?error=$em");
   exit();
}

} else { 
   $em = "Veuillez vous connecter d'abord";
   header("Location: ../login.php?error=$em");
   exit();
}
