<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/User.php";
    
    if (!isset($_GET['id'])) {
    	 header("Location: user.php");
    	 exit();
    }
    $id = $_GET['id'];
    $user = get_user_by_id($conn, $id);

    if ($user == 0) {
    	 header("Location: user.php");
    	 exit();
    }

     $data = array($id, "employé");
     delete_user($conn, $data);
     $sm = "Utilisateur supprimé avec succès";
     header("Location: user.php?success=$sm");
     exit();

 }else{ 
   $em = "Veuillez vous connecter d'abord";
   header("Location: login.php?error=$em");
   exit();
}
 ?>