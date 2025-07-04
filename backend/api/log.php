<?php
include 'config.php';
include 'auth.php';

$result = $conn->query("
    SELECT a.*, u.name as user_name 
    FROM activity_log a 
    JOIN users u ON a.user_id = u.id 
    ORDER BY timestamp DESC LIMIT 20
");

$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}
echo json_encode($logs);
?>
