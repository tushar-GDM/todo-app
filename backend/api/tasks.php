<?php
include 'config.php';
include 'auth.php'; // Get $userId from JWT
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Get all tasks
    $result = $conn->query("SELECT * FROM tasks");
    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
    echo json_encode($tasks);
}

elseif ($method === 'POST') {
    // Add new task
    $data = json_decode(file_get_contents("php://input"));
    $title = $data->title;
    $desc = $data->description;
    $assigned = $data->assigned_to;
    $priority = $data->priority ?? 'Medium';

    $stmt = $conn->prepare("INSERT INTO tasks (title, description, assigned_to, status, priority, updated_at) VALUES (?, ?, ?, 'Todo', ?, NOW())");
    $stmt->bind_param("ssis", $title, $desc, $assigned, $priority);
    $stmt->execute();

    $taskId = $stmt->insert_id;
    $conn->query("INSERT INTO activity_log (action, user_id, task_id) VALUES ('created task: $title', $userId, $taskId)");

    echo json_encode(["status" => "created"]);
}

elseif ($method === 'PUT') {
    // Update task
    $data = json_decode(file_get_contents("php://input"));
    $id = $data->id;
    $title = $data->title;
    $desc = $data->description;
    $assigned = $data->assigned_to;
    $status = $data->status;
    $priority = $data->priority;

    $stmt = $conn->prepare("UPDATE tasks SET title=?, description=?, assigned_to=?, status=?, priority=?, updated_at=NOW() WHERE id=?");
    $stmt->bind_param("ssissi", $title, $desc, $assigned, $status, $priority, $id);
    $stmt->execute();

    $conn->query("INSERT INTO activity_log (action, user_id, task_id) VALUES ('updated task: $title', $userId, $id)");

    echo json_encode(["status" => "updated"]);
}

elseif ($method === 'DELETE') {
    // Delete task
    parse_str(file_get_contents("php://input"), $_DELETE);
    $id = $_DELETE['id'];

    $conn->query("DELETE FROM tasks WHERE id=$id");
    $conn->query("INSERT INTO activity_log (action, user_id, task_id) VALUES ('deleted task ID: $id', $userId, $id)");

    echo json_encode(["status" => "deleted"]);
}
?>
