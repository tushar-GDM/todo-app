<?php
include 'config.php';
include 'auth.php';

$data = json_decode(file_get_contents("php://input"));
$taskId = $data->task_id;

// Get user with least assigned (Todo + In Progress) tasks
$sql = "SELECT u.id, COUNT(t.id) as total 
        FROM users u 
        LEFT JOIN tasks t ON u.id = t.assigned_to AND t.status IN ('Todo', 'In Progress') 
        GROUP BY u.id ORDER BY total ASC LIMIT 1";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
$leastLoadedUser = $row['id'] ?? null;

if ($leastLoadedUser) {
    $conn->query("UPDATE tasks SET assigned_to=$leastLoadedUser WHERE id=$taskId");
    $conn->query("INSERT INTO activity_log (action, user_id, task_id) VALUES ('smart assigned to user ID $leastLoadedUser', $userId, $taskId)");
    echo json_encode(["assigned_to" => $leastLoadedUser]);
} else {
    echo json_encode(["error" => "No user found"]);
}
?>
