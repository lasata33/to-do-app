<?php
require 'db.php';

$date = $_GET['date'] ?? date('Y-m-d');

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE task_date = ? ORDER BY created_at DESC");
$stmt->execute([$date]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($tasks);
?>