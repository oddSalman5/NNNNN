<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}
require_once "database.php";

$sql = "SELECT * FROM tasks";
$result = mysqli_query($conn, $sql);
$tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>View Tasks</title>
</head>

<body>
    <!-- menu option -->
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="view_tasks.php">View Tasks</a></li>
        </ul>
    </nav>

    <div class="container">
        <h1>View Tasks</h1>
        <?php foreach ($tasks as $task) : ?>
            <div class="task">
                <h2><?= $task['task'] ?></h2>
                <p>Description: <?= $task['description'] ?></p>
                <p>Completion Date: <?= $task['completion_date'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>

</body>

</html>
