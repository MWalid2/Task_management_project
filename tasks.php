<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";

    $text = "Toutes les tâches";
    if (isset($_GET['due_date']) && $_GET['due_date'] == "Due Today") {
        $text = "Dû aujourd’hui";
        $tasks = get_all_tasks_due_today($conn);
    } else if (isset($_GET['due_date']) && $_GET['due_date'] == "Overdue") {
        $text = "En retard";
        $tasks = get_all_tasks_overdue($conn);
    } else if (isset($_GET['due_date']) && $_GET['due_date'] == "Due this week") {
        $text = "Dû cette semaine";
        $tasks = get_all_tasks_due_this_week($conn);
    } else {
        $tasks = get_all_tasks($conn);
    }

    $users = get_all_users($conn);

    $tasks_by_title = [];
    if (is_array($tasks)) {
        foreach ($tasks as $task) {
            $title = $task['title'];
            $description = $task['description']; // Ajouter la description sans l'afficher
            $date_created = $task['created_at']; // Ajouter la date de création sans l'afficher
            $user_id = $task['assigned_to'];

            if (!isset($tasks_by_title[$title])) {
                $tasks_by_title[$title] = [
                    'due_date' => $task['due_date'], 
                    'description' => $description, // Stocker la description sans l'afficher
                    'date_created' => $date_created, // Stocker la date de création sans l'afficher
                    'statuses' => [] // Stocker les statuts des utilisateurs
                ];
            }

            $tasks_by_title[$title]['statuses'][$user_id] = $task['status']; // Associer le statut à l'utilisateur
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Toutes les tâches</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <input type="checkbox" id="checkbox">
    <?php include "inc/header.php" ?>
    <div class="body">
        <?php include "inc/nav.php" ?>
        <section class="section-1">
            <h4 class="title-2">
                <a href="create_task.php" class="btn">Créer une tâche</a>
                <a href="tasks.php?due_date=Due Today">Dû aujourd’hui</a>
                <a href="tasks.php?due_date=Overdue">En retard</a>
                <a href="tasks.php?due_date=Due this week">Dû cette semaine</a>
                <a href="tasks.php">Toutes les tâches</a>
            </h4>
            <h4 class="title-2"><?=$text?></h4>
            <?php if (isset($_GET['success'])) { ?>
                <div class="success" role="alert">
                    <?php echo stripcslashes($_GET['success']); ?>
                </div>
            <?php } ?>

            <?php if (!empty($tasks_by_title)) { ?>
            <table class="main-table">
                <thead>
                    <tr>
                        <th>Tâches/Utilisateurs</th>
                        <?php foreach ($users as $user) { ?>
                            <th><?= $user['username'] ?></th>
                        <?php } ?>
                        <th>Date de délai</th>
                        <th>Actions</th> <!-- Nouvelle colonne pour les actions -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks_by_title as $title => $task_data) { ?>
                        <tr>
                            <td><?= $title ?></td>
                            <?php foreach ($users as $user) { ?>
                                <td>
                                    <?php 
                                    // Afficher le statut si l'utilisateur a une tâche associée
                                    echo isset($task_data['statuses'][$user['id']]) ? $task_data['statuses'][$user['id']] : '-';
                                    ?>
                                </td>
                            <?php } ?>
                            <td><?= $task_data['due_date'] ?></td>
                            <td>
                                
                                <a href="delete-task.php?id=<?php echo $task['id']; ?>" class="delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?');">
                                    <i class="fa fa-trash"></i> Supprimer
                                </a>
                            </td> <!-- Colonne d'actions -->
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <h3>Aucune tâche trouvée</h3>
            <?php } ?>
        </section>
    </div>

<script type="text/javascript">
    var active = document.querySelector("#navList li:nth-child(4)");
    active.classList.add("active");
</script>
</body>
</html>
<?php 
} else { 
    $em = "Veuillez vous connecter d'abord";
    header("Location: login.php?error=$em");
    exit();
}
?>
