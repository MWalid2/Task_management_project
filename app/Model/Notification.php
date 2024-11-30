<?php  
// Include the Task model or file where the required functions are defined
include_once 'Task.php';

/**
 * Get all notifications for a specific user.
 *
 * @param PDO $conn Database connection object.
 * @param int $id User ID.
 * @return array|int Returns an array of notifications or 0 if no notifications exist.
 */
function get_all_my_notifications($conn, $id) {
    $sql = "SELECT * FROM notifications WHERE recipient = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return 0;
    }
}

/**
 * Count the number of unread notifications for a specific user.
 *
 * @param PDO $conn Database connection object.
 * @param int $id User ID.
 * @return int Number of unread notifications.
 */
function count_notification($conn, $id) {
    $sql = "SELECT id FROM notifications WHERE recipient = ? AND is_read = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    return $stmt->rowCount();
}

/**
 * Insert a new notification into the database.
 *
 * @param PDO $conn Database connection object.
 * @param array $data Array containing message, recipient, type, and task_id.
 * @return void
 */
function insert_notification($conn, $data) {
    $sql = "INSERT INTO notifications (message, recipient, type, task_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}
function insert_notification_1($conn, $data) {
    $sql = "INSERT INTO notifications (message, recipient, type) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

/**
 * Send task notifications based on tasks due today and this week.
 *
 * @param PDO $conn Database connection object.
 * @return void
 */
function send_task_notifications($conn) {
    // Get all tasks due today
    $tasks_due_today = get_all_tasks_due_today($conn); // Ensure this function is defined in 'Task.php'
    $tasks_due_this_week = get_all_tasks_due_this_week($conn); // Ensure this function is defined in 'Task.php'

    // Notify for tasks due today
    foreach ($tasks_due_today as $task) {
        $recipient = $task['assigned_to'];
        $message = "Votre tâche '{$task['title']}' est due aujourd'hui. Veuillez la terminer.";
        $type = "Tâche due aujourd'hui";

        // Check if a similar notification already exists
        if (!notification_exists($conn, $recipient, $type, $task['id'])) {
            $notif_data = [$message, $recipient, $type, $task['id']];
            insert_notification($conn, $notif_data);
        }
    }

    // Notify for tasks due this week
    foreach ($tasks_due_this_week as $task) {
        $recipient = $task['assigned_to'];
        $message = "Votre tâche '{$task['title']}' est due cette semaine. Veuillez la planifier.";
        $type = "Tâche due cette semaine";

        // Check if a similar notification already exists
        if (!notification_exists($conn, $recipient, $type, $task['id'])) {
            $notif_data = [$message, $recipient, $type, $task['id']];
            insert_notification($conn, $notif_data);
        }
    }
}

/**
 * Check if a similar notification already exists.
 *
 * @param PDO $conn Database connection object.
 * @param int $recipient Recipient user ID.
 * @param string $type Notification type.
 * @param int $task_id Task ID.
 * @return bool True if a similar notification exists, otherwise false.
 */
function notification_exists($conn, $recipient, $type, $task_id) {
    $sql = "SELECT id FROM notifications WHERE recipient = ? AND type = ? AND task_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$recipient, $type, $task_id]);

    return $stmt->rowCount() > 0;
}
function notification_make_read($conn, $user_id, $notification_id) {
    $sql = "UPDATE notifications SET is_read = 1 WHERE id = ? AND recipient = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$notification_id, $user_id]);

    return $stmt->rowCount(); // Optionally return the number of affected rows
}
