<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';


if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userid = $_SESSION['userid'];

$todos_result = $conn->prepare("SELECT id, title, iscompleted, date FROM tbllist WHERE type = 2 AND userid = ? ORDER BY id DESC");
$todos_result->bind_param("i", $userid);
$todos_result->execute();
$result = $todos_result->get_result();

$selected_todo = ['id' => '', 'title' => '', 'date' => '', 'iscompleted' => 0];
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $res = $conn->query("SELECT * FROM tbllist WHERE id = $id AND userid = $userid");
    if ($res->num_rows > 0) {
        $selected_todo = $res->fetch_assoc();
        $selected_todo['iscompleted'] = (int)$selected_todo['iscompleted']; 
    }
}
?>

<!-- Link to external CSS -->
<link rel="stylesheet" href="styles/todo.css">

<div class="todo-header-wrapper">
    <img src="media/note/btodo.png" alt="To-Do Banner">
    <h2>To-Do List</h2>
</div>

<div class="container">
    <div class="card">
        <div class="card-header-black"></div>
        <div class="card-body p-0 mt-4">
            <div class="table-container">
                <table class="table table-bordered">
                    <thead class="todo-table-header">
                        <tr>
                            <th scope="col">TASK</th>
                            <th scope="col" class="text-center">PRIORITY</th>
                            <th scope="col" class="text-center">DEADLINE</th>
                            <th scope="col" class="text-center">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($todo = $result->fetch_assoc()): ?>
                            <?php if (!isset($_GET['sfu']) || $_GET['sfu'] != 1 || $_GET['id'] != $todo['id']): ?>
                                <tr>
                                    <th scope="row" class="col=6" data-label="TASK"><?= htmlspecialchars($todo['title']) ?></th>
                                    <td class="text-center col-2" data-label="PRIORITY">
                                        <?php
                                        $date = $todo['date'];
                                        $today = new DateTime();
                                        $deadline = new DateTime($date);
                                        $interval = $today->diff($deadline);
                                        $daysLeft = (int) $interval->format('%r%a');

                                        if ($todo['iscompleted'] == 1) {
                                            $priority = "<span style='color:green;font-weight:bold;'>COMPLETED</span>";
                                        } elseif ($daysLeft < 1) {
                                            $priority = "<span style='color:purple;font-weight:bold;'>DELAYED</span>";
                                        } elseif ($daysLeft == 1) {
                                            $priority = "<span style='color:red;font-weight:bold;'>HIGH</span>";
                                        } elseif ($daysLeft == 2) {
                                            $priority = "<span style='color:orange;font-weight:bold;'>MEDIUM</span>";
                                        } else {
                                            $priority = "<span style='color:blue;font-weight:bold;'>LOW</span>";
                                        }

                                        echo $priority;
                                        ?>
                                    </td>
                                    <td class="col-2 text-center" data-label="DEADLINE">
                                        <?= date('F j, Y', strtotime($todo['date'])) ?>
                                    </td>
                                    <td class="col-2 text-center" data-label="ACTION">
                                        <a href="./dashboard.php?page=todo&id=<?= htmlspecialchars($todo['id']) ?>&action=2&sfu=1" class="btn btn-sm">Update</a>
                                        <a href="./todos.php?id=<?= htmlspecialchars($todo['id']) ?>&action=3" class="btn btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <?php if (isset($_GET['sfu']) && $_GET['sfu'] == 1 && $_GET['id'] == $todo['id']): ?>
                                <tr>
                                    <form action="todos.php?action=2&id=<?= $_GET['id'] ?>" method="post">
                                        <th scope="row" colspan="2">
                                            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($todo['title']) ?>" required>
                                        </th>
                                        <td>
                                            <input type="date" class="form-control" name="date" value="<?= htmlspecialchars($todo['date']) ?>" required>
                                        </td>
                                        <td>
                                            <a href="./dashboard.php?page=todo" class="btn btn-sm">Cancel</a>
                                            <button type="submit" class="btn btn-sm">Update</button>
                                        </td>
                                    </form>
                                </tr>
                            <?php endif; ?>
                        <?php endwhile; ?>

                        <?php if (isset($_GET['sf']) && $_GET['sf'] == 1): ?>
                            <tr>
                                <form action="todos.php?action=1" method="post">
                                    <th scope="row" colspan="2">
                                        <input type="text" class="form-control" name="title" placeholder="Enter Task/To Do" required>
                                    </th>
                                    <td>
                                        <input type="date" class="form-control" name="date" required>
                                    </td>
                                    <td>
                                        <a href="./dashboard.php?page=todo" class="btn btn-sm">Cancel</a>
                                        <button type="submit" class="btn btn-sm">Save</button>
                                    </td>
                                </form>
                            </tr>
                        <?php endif; ?>

                        <tr>
                            <th scope="row" colspan="4">
                                <a href="./dashboard.php?page=todo&sf=1&action=1" class="btn mt-3 w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="float-right bi bi-plus-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                                    </svg> ADD TASK
                                </a>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
