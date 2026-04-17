<?php
require 'db.php';

$date = $_GET['date'] ?? date('Y-m-d');

$stmt = $pdo->prepare("SELECT * FROM notes WHERE note_date = ? ORDER BY created_at DESC");
$stmt->execute([$date]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($notes);
?>