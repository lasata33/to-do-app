<?php
require 'db.php';

$error = "";

// Fetch the existing task
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $id = $_POST["id"];

    if ($title === "") {
        $error = "Task title cannot be empty!";
    } else {
        $stmt = $pdo->prepare("UPDATE tasks SET title = ? WHERE id = ?");
        $stmt->execute([$title, $id]);
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
</head>
<body>
    <h1>Edit Task</h1>

    <?php if ($error): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="edit.php">
        <!-- Hidden field to carry the task ID -->
        <input type="hidden" name="id" value="<?= $task['id'] ?>">
        
        <input 
            type="text" 
            name="title" 
            value="<?= htmlspecialchars($task['title']) ?>"
            required
        >
        <button type="submit">Update Task</button>
    </form>

    <a href="index.php">← Back to tasks</a>
</body>
</html>