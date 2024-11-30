<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Task.php";
    
    if (!isset($_GET['id'])) {
    	 header("Location: tasks.php");
    	 exit();
    }
    $id = $_GET['id'];
    $task = get_task_by_id($conn, $id);

    if ($task == 0) {
    	 header("Location: tasks.php");
    	 exit();
    }

     $data = array($id);
     delete_task($conn, $data);
     $sm = "Tache supprimée avec succès";
     header("Location: tasks.php?success=$sm");
     exit();

 }else{ 
   $em = "Veuillez vous connecter d'abord";
   header("Location: login.php?error=$em");
   exit();
}
 ?>