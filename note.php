

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';

if (!isset($_SESSION['userid'])) {
  die("Unauthorized access. Please log in.");
}

$userid = $_SESSION['userid'];

$notes_result = $conn->query("SELECT id, title, about FROM tbllist WHERE type = 1 AND userid = $userid ORDER BY id DESC");

$selected_note = ['id' => '', 'title' => '', 'about' => '', 'notes' => ''];
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $stmt = $conn->prepare("SELECT * FROM tbllist WHERE id = ? AND userid = ?");
  $stmt->bind_param("ii", $id, $userid);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows > 0) {
    $selected_note = $res->fetch_assoc();
  }
  $stmt->close();
}
?>

<link rel="stylesheet" href="styles/note.css">
<div class="todo-header-wrapper">
  <img src="media/note/btodo.png" alt="To-Do Banner">
  <h2>Notes</h2>
</div>

<div class="container">
  <div class="card note-card">
    <div class="note-header"></div>
    <div class="note-subheader"></div>
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-4 col-12">
          <div class="list-container">
            <ol class="list-group">
              <?php foreach ($notes_result as $note): ?>
                <a href="dashboard.php?page=note&sf=1&action=2&id=<?= $note['id'] ?>" class="text-decoration-none text-dark">
                  <li class="list-group-item d-flex justify-content-between align-items-center mb-2" style="border: none;">
                    <div class="ms-2 me-auto">
                      <div class="fw-bold"><?= htmlspecialchars($note['title']) ?></div>
                      <small><?= htmlspecialchars($note['about']) ?></small>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                         class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                      <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                    </svg>
                  </li>
                </a>
              <?php endforeach; ?>
            </ol>
          </div>
          <div class="add-note-btn">
            <a href="dashboard.php?page=note&sf=1&action=1" class="btn w-100">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                   class="float-right bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
              </svg> ADD NOTE
            </a>
          </div>
        </div>

        <div class="col-md-8 col-12 p-3 note-content">
          <?php if (isset($_GET['action']) && $_GET['action'] == 1): ?>
            <h4>Add New Note</h4>
            <form action="notes.php?action=1" method="post">
              <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" id="title" required placeholder="Enter title">
              </div>
              <div class="mb-3">
                <label for="about" class="form-label">About</label>
                <input type="text" class="form-control" name="about" id="about" placeholder="Short description">
              </div>
              <div class="mb-3">
                <label for="notes" class="form-label">Notes:</label>
                <textarea class="form-control" id="notes" name="notes" rows="5" required placeholder="Write your notes here..."></textarea>
              </div>
              <div class="d-flex justify-content-end">
                <a href="dashboard.php?page=note" class="btn me-2">Cancel</a>
                <button type="submit" class="btn">Save</button>
              </div>
            </form>

          <?php elseif (isset($_GET['action']) && $_GET['action'] == 2 && !empty($selected_note['id'])): ?>
            <div class="mb-3">
              <h5><strong>Title:</strong> <?= htmlspecialchars($selected_note['title']) ?></h5>
            </div>
            <div class="mb-3">
              <h5><strong>About:</strong> <?= htmlspecialchars($selected_note['about']) ?></h5>
            </div>
            <div class="mb-3">
              <h5>
                <strong>Notes:</strong>
                <div class="mt-2"><?= nl2br(htmlspecialchars($selected_note['notes'])) ?></div>
              </h5>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-3">
              <a href="dashboard.php?page=note&sf=1&action=2&id=<?= $selected_note['id'] ?>&edit=1" class="btn">Edit</a>
              <a href="notes.php?id=<?= $selected_note['id'] ?>&action=3" class="btn">Delete</a>
            </div>

            <?php if (isset($_GET['edit']) && $_GET['edit'] == 1): ?>
              <hr>
              <h5>Edit Note</h5>
              <form action="notes.php?action=2&id=<?= $selected_note['id'] ?>" method="post">
                <div class="mb-3">
                  <label for="title" class="form-label">Title</label>
                  <input type="text" class="form-control" name="title" id="title" required value="<?= htmlspecialchars($selected_note['title']) ?>">
                </div>
                <div class="mb-3">
                  <label for="about" class="form-label">About</label>
                  <input type="text" class="form-control" name="about" id="about" value="<?= htmlspecialchars($selected_note['about']) ?>">
                </div>
                <div class="mb-3">
                  <label for="notes" class="form-label">Notes:</label>
                  <textarea class="form-control" id="notes" name="notes" rows="5" required><?= htmlspecialchars($selected_note['notes']) ?></textarea>
                </div>
                <div class="d-flex justify-content-end gap-2">
                  <a href="dashboard.php?page=note&sf=1&action=2&id=<?= $selected_note['id'] ?>" class="btn">Cancel</a>
                  <button type="submit" class="btn">Update</button>
                </div>
              </form>
            <?php endif; ?>
          <?php else: ?>
            <p>Select a note or click "Add Note" to begin.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
