<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id'])) {

    if (
        isset($_POST['title']) &&
        isset($_POST['description']) &&
        isset($_POST['assigned_to']) && // This is now an array of user IDs
        isset($_POST['due_date']) &&
        $_SESSION['role'] == 'admin'
    ) {
        include "../DB_connection.php";

        // Validation function
        function validate_input($data) {
            if (is_array($data)) {
                return array_map('validate_input', $data);
            }
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        // Validate form inputs
        $title = validate_input($_POST['title']);
        $description = validate_input($_POST['description']);
        $assigned_to = validate_input($_POST['assigned_to']); // Array of user IDs
        $due_date = validate_input($_POST['due_date']);

        // Check for empty fields
        if (empty($title)) {
            $em = "Titre est demandé";
            header("Location: ../create_task.php?error=" . urlencode($em));
            exit();
        } else if (empty($description)) {
            $em = "Description est demandée";
            header("Location: ../create_task.php?error=" . urlencode($em));
            exit();
        } else if (empty($assigned_to)) {
            $em = "Veuillez sélectionner au moins un utilisateur";
            header("Location: ../create_task.php?error=" . urlencode($em));
            exit();
        }

        // Include models
        include "Model/Task.php";
        include "Model/Notification.php";

        // Insert a task for each selected user
        foreach ($assigned_to as $user_id) {
            $data = array($title, $description, $user_id, $due_date);
            insert_task($conn, $data);

            // Insert a notification for each assigned user
            $notif_data = array(
                "'$title' vous a été attribué. S’il vous plaît, relisez-la et commencez à travailler dessus.",
                $user_id,
                'Nouvelle tâche assignée'
            );
            insert_notification_1($conn, $notif_data);
        }

        // Redirect with success message
        $em = "Tâche(s) créée(s) avec succès.";
        header("Location: ../create_task.php?success=" . urlencode($em));
        exit();

    } else {
        $em = "Erreur inconnue";
        header("Location: ../create_task.php?error=" . urlencode($em));
        exit();
    }

} else { 
    $em = "Veuillez vous connecter d'abord.";
    header("Location: ../login.php?error=" . urlencode($em));
    exit();
}
