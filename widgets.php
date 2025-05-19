<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';

$userId = $_SESSION['userid']; 

$todos_result = $conn->query("SELECT id, title, iscompleted, date FROM tbllist WHERE userid = $userId AND type = 2 ORDER BY id DESC");

$critical_result = $conn->query("SELECT id, title, iscompleted, date FROM tbllist WHERE userid = $userId AND type = 2 AND iscompleted = 0 AND DATEDIFF(date, CURDATE()) IN (1, 2)");

$notes_result = $conn->query("SELECT id, title, about, date FROM tbllist WHERE userid = $userId AND type = 1 ORDER BY id DESC");


$selected_todo = ['id' => '', 'title' => '', 'date' => '', 'iscompleted' => 0];
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $res = $conn->query("SELECT * FROM tbllist WHERE id = $id AND userid = $userId");
    if ($res->num_rows > 0) {
        $selected_todo = $res->fetch_assoc();
        $selected_todo['isCompleted'] = (int)$selected_todo['isCompleted'];
    }
}
?>

<div class="row">
    <!-- To-Do List Card -->
    <div class="col-md-6 col-sm-12">
        <section class="card">
            <div class="card-header p-2 text-center" style="background-color: #C2FFF6; border-radius: 20px;">To-Do List</div>
            <?php if ($todos_result->num_rows === 0): ?>
                <div class="card-body">
                    <p class="empty">You have no tasks yet.</p>
                    <p><br>Start by adding your first to-do item to keep track of what <span class="highlight-break">matters.</span></p>
                </div>
            <?php endif; ?>
            <div class="list-container" style="max-height: 350px; overflow-y: auto;">
                <ol class="list-group">
                    <?php while ($todo = $todos_result->fetch_assoc()): ?>
                        <a href="dashboard.php?page=note&sf=1&action=2&id=<?= $todo['id'] ?>" class="text-decoration-none text-dark">
                            <li class="list-group-item d-flex justify-content-between align-items-center" style="cursor:pointer; border: none;">
                                <div>
                                    <input type="checkbox" 
                                        style="margin-right: 10px;" 
                                        <?= $todo['iscompleted'] == 1 ? 'checked' : '' ?>
                                        onchange="updateCompletionStatus(<?= $todo['id'] ?>, this)">
                                </div>
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold"><?= htmlspecialchars($todo['title']) ?></div>
                                    <small><?= (new DateTime($todo['date']))->format('M d, Y') ?></small>
                                </div>
                                <span><b>
                                    <?php
                                        $date = $todo['date'];
                                        $today = new DateTime();
                                        $deadline = new DateTime($date);
                                        $interval = $today->diff($deadline);
                                        $daysLeft = (int) $interval->format('%r%a');

                                        if ($todo['iscompleted'] == 1) {
                                            echo "<span style='color:green;'>Completed</span>";
                                        } elseif ($daysLeft < 1) {
                                            echo "<span style='color:purple;'>DELAYED</span>";
                                        } elseif ($daysLeft == 1) {
                                            echo "<span style='color:red;'>HIGH</span>";
                                        } elseif ($daysLeft == 2) {
                                            echo "<span style='color:orange;'>MEDIUM</span>";
                                        } else {
                                            echo "<span style='color:blue;'>LOW</span>";
                                        }
                                    ?>
                                </b></span>
                            </li>
                        </a>
                    <?php endwhile; ?>
                </ol>
            </div>
        </section>

        <!-- Critical List Card -->
        <section class="card mt-3">
            <div class="card-header p-2 text-center" style="background-color: #FFE3E3; border-radius: 20px;">Critical List (High & Medium)</div>

            <?php
            $hasCritical = false;
            ?>
            <div class="list-container" style="max-height: 350px; overflow-y: auto;">
                <ol class="list-group">
                    <?php while ($crit = $critical_result->fetch_assoc()):
                        $date = $crit['date'];
                        $today = new DateTime();
                        $deadline = new DateTime($date);
                        $interval = $today->diff($deadline);
                        $daysLeft = (int) $interval->format('%r%a');

                       
                        if ($daysLeft === 1 || $daysLeft === 2):
                            $hasCritical = true;
                    ?>
                        <a href="dashboard.php?page=note&sf=1&action=2&id=<?= $crit['id'] ?>" class="text-decoration-none text-dark">
                            <li class="list-group-item d-flex justify-content-between align-items-center" style="cursor:pointer; border: none;">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold"><?= htmlspecialchars($crit['title']) ?></div>
                                    <small><?= (new DateTime($crit['date']))->format('M d, Y') ?></small>
                                </div>
                                <span><b>
                                    <?php
                                        if ($crit['iscompleted'] == 1) {
                                            echo "<span style='color:green;'>Completed</span>";
                                        } elseif ($daysLeft === 1) {
                                            echo "<span style='color:red;'>HIGH</span>";
                                        } elseif ($daysLeft === 2) {
                                            echo "<span style='color:orange;'>MEDIUM</span>";
                                        }
                                    ?>
                                </b></span>
                            </li>
                        </a>
                    <?php endif; endwhile; ?>

                    <?php if (!$hasCritical): ?>
                        <li class="list-group-item text-center text-muted">No critical tasks.</li>
                    <?php endif; ?>
                </ol>
            </div>
        </section>
    </div>

    <!-- Notes Card -->
    <div class="col-md-6 col-sm-12">
        <section class="card">
            <div class="card-header p-2 text-center" style="background-color: #C2FFF6; border-radius: 20px;">Notes</div>
            <?php if ($notes_result->num_rows === 0): ?>
                <div class="card-body">
                    <p class="empty">You have no notes yet.</p>
                    <p><br>Use notes to store ideas, reminders, or important info!</p>
                </div>
            <?php else: ?>
                <div class="list-container" style="max-height: 350px; overflow-y: auto;">
                    <ol class="list-group">
                        <?php while ($note = $notes_result->fetch_assoc()): ?>
                            <a href="dashboard.php?page=note&sf=1&action=1&id=<?= $note['id'] ?>" class="text-decoration-none text-dark">
                                <li class="list-group-item d-flex justify-content-between align-items-start" style="cursor:pointer; border: none;">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold"><?= htmlspecialchars($note['title']) ?></div>
                                        <div class="text-muted" style="font-size: 0.85em;">
                                            <?= nl2br(htmlspecialchars($note['about'])) ?>
                                        </div>
                                        <small><?= htmlspecialchars($note['date']) ?></small>
                                    </div>
                                    <div class="d-flex flex-column align-items-center">
                                        <!-- Note Icon -->
                                        <i class="bi bi-journal-text mb-2"></i>

                                        <!-- Arrow Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                            class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                                            <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/>
                                        </svg>
                                    </div>
                                </li>
                            </a>
                        <?php endwhile; ?>
                    </ol>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<!-- JS for updating task status -->
<script>
    function updateCompletionStatus(todoId, checkbox) {
        const isCompleted = checkbox.checked ? 1 : 0;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_todo.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                console.log('Server response:', xhr.responseText);
                window.location.reload(); 
            } else {
                console.error('Error updating task status');
            }
        };
        xhr.send(`id=${todoId}&iscompleted=${isCompleted}`);
    }
</script>

