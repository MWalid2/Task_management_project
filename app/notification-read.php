<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "../DB_connection.php";
    include "Model/Notification.php";

    if (isset($_GET['notification_id'])) {
        $notification_id = $_GET['notification_id'];
        
        // Mark notification as read
        try {
            $result = notification_make_read($conn, $_SESSION['id'], $notification_id);

            if ($result > 0) {
                header("Location: ../notifications.php?success=La notification est lisée");
            } else {
                header("Location: ../notifications.php?error=La notification n'est pas trouvée");
            }
        } catch (Exception $e) {
            header("Location: ../notifications.php?error=" . urlencode($e->getMessage()));
        }

        exit();
    } else {
        header("Location: index.php");
        exit();
    }
} else { 
    $em = "Veuillez vous connecter d'abord";
    header("Location: login.php?error=$em");
    exit();
}
