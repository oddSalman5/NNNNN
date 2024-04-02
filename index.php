<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
}
require_once "database.php";

// Insert Task
if (isset($_POST["add_task"])) {
    $task = $_POST["task"];
    $description = $_POST["description"];
    $completion_date = $_POST["completion_date"];

    $sql = "INSERT INTO tasks (task, description, completion_date) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "sss", $task, $description, $completion_date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Delete Task
if (isset($_GET["delete_task"])) {
    $task_id = $_GET["delete_task"];

    $sql = "DELETE FROM tasks WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $task_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Update Task
if (isset($_POST["update_task"])) {
    $task_id = $_POST["task_id"];
    $task = $_POST["task"];
    $description = $_POST["description"];
    $completion_date = $_POST["completion_date"];

    $sql = "UPDATE tasks SET task = ?, description = ?, completion_date = ? WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssi", $task, $description, $completion_date, $task_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>User Dashboard</title>
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
        <h1>Welcome to TaskMaster</h1>
        <a href="logout.php" class="btn btn-warning">Logout</a>
    </div>

    <div class="container">
        <h1>Task Manager</h1>
        <!-- Task form -->
        <form action="index.php" method="POST">
            <div class="form-group">
                <input type="text" name="task" placeholder="Enter your task" required>
            </div>
            <div class="form-group">
                <input type="text" name="description" placeholder="Description">
            </div>
            <div class="form-group">
                <input type="date" name="completion_date" placeholder="Completion Date" required>
            </div>
            <button type="submit" name="add_task" class="btn btn-primary">Add Task</button>
        </form>

        <!-- Display tasks -->
        <div class="task-list">
            <h2>Tasks:</h2>
            <?php
            $sql = "SELECT * FROM tasks";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='task'>";
                    echo "<p>" . $row['task'] . "</p>";
                    echo "<p>" . $row['description'] . "</p>";
                    echo "<p>" . $row['completion_date'] . "</p>";
                    echo "<a href='index.php?delete_task=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a>";
                    echo "<button class='btn btn-primary btn-sm' onclick='showUpdateForm(" . $row['id'] . ", \"" . $row['task'] . "\", \"" . $row['description'] . "\", \"" . $row['completion_date'] . "\")'>Update</button>";
                    echo "</div>";
                }
            } else {
                echo "No tasks added yet.";
            }
            ?>
        </div>

        <!-- Update Task Form (Hidden by default) -->
        <div id="updateForm" style="display: none;">
            <h2>Update Task</h2>
            <form id="updateTaskForm" action="index.php" method="POST">
                <input type="hidden" id="updateTaskId" name="task_id">
                <div class="form-group">
                    <input type="text" id="updateTask" name="task" placeholder="Enter updated task" required>
                </div>
                <div class="form-group">
                    <input type="text" id="updateDescription" name="description" placeholder="Enter updated description">
                </div>
                <div class="form-group">
                    <input type="date" id="updateCompletionDate" name="completion_date" placeholder="Enter updated completion date" required>
                </div>
                <button type="submit" name="update_task" class="btn btn-success">Update Task</button>
            </form>
        </div>
    </div>

    <script>
        function showUpdateForm(taskId, task, description, completionDate) {
            document.getElementById('updateTaskId').value = taskId;
            document.getElementById('updateTask').value = task;
            document.getElementById('updateDescription').value = description;
            document.getElementById('updateCompletionDate').value = completionDate;
            document.getElementById('updateForm').style.display = 'block';
        }
    </script>

</body>

</html>
