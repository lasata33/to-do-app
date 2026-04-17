<?php
require 'db.php';

$title = trim($_POST['title']);
$body = trim($_POST['body']);
$date = $_POST['date'] ?? date('Y-m-d');

if ($title !== '' && $body !== '') {
    $stmt = $pdo->prepare("INSERT INTO notes (title, body, note_date) VALUES (?, ?, ?)");
    $stmt->execute([$title, $body, $date]);
}

header("Location: index.php");
exit;
?>