<?php
require 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);

    if ($title === "") {
        $error = "Task title cannot be empty!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO tasks (title, task_date) VALUES (?, CURDATE())");
$stmt->execute([$title]);
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task</title>
</head>
<body>
    <h1>Add New Task</h1>

    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="add.php">
        <input type="text" name="title" placeholder="Enter task..." required>
        <button type="submit">Add Task</button>
    </form>

    <a href="index.php">← Back to tasks</a>
</body>
</html>