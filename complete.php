<?php
require 'db.php';

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $stmt = $pdo->prepare("UPDATE tasks SET status = 'completed' WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: index.php");
exit;
?>