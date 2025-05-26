<?php
include '../auth/connection.php';

$message = "";
$showMessage = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_score'])) {
    $user_id = $_POST['user_id'];
    $points = $_POST['points'];

    $stmt = $con->prepare("UPDATE users SET score = ? WHERE users_id = ?");
    $stmt->bind_param("ii", $points, $user_id);

    if ($stmt->execute()) {
        $message = "✅ Score updated successfully.";
        $showMessage = true;
    } else {
        $message = "❌ Error: " . $stmt->error;
        $showMessage = true;
    }
}

$users = $con->query("SELECT users_id, name, score FROM users ORDER BY score DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Judge Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 56px;
            background:
                    linear-gradient(135deg, rgba(30, 60, 114, 0.8), rgba(109, 158, 255, 0.8)),
                    url("https://images.pexels.com/photos/7233099/pexels-photo-7233099.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2");
            background-attachment: fixed;
            background-size: cover;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .card {
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        #searchInput {
            max-width: 300px;
        }
    </style>
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Judge Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar text-white">
            <a href="../index.php">🏠 Home</a>
            <a href="#">👥 Manage Scores</a>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
            <h2 class="text-center mb-4 text-white">🧑‍⚖️ Judge Dashboard</h2>

            <?php if ($showMessage): ?>
                <div class="alert alert-success text-center"><?= $message ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Participants and Their Scores</h5>
                    <input type="text" id="searchInput" class="form-control" placeholder="Search participant...">
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-center" id="participantsTable">
                            <thead class="table-light">
                            <tr>
                                <th>Participant</th>
                                <th>Current Score</th>
                                <th>Update Score</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while ($user = $users->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($user['name']) ?></td>
                                    <td><?= $user['score'] ?></td>
                                    <td>
                                        <form method="POST" class="d-flex justify-content-center align-items-center gap-2">
                                            <input type="hidden" name="user_id" value="<?= $user['users_id'] ?>">
                                            <input type="number" name="points" min="0" max="100" value="<?= $user['score'] ?>" class="form-control" style="max-width: 100px;" required>
                                            <button type="submit" name="update_score" class="btn btn-primary btn-sm">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Live search filter
    const searchInput = document.getElementById('searchInput');
    const tableRows = document.querySelectorAll('#participantsTable tbody tr');

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        tableRows.forEach(row => {
            const nameCell = row.querySelector('td:first-child');
            const name = nameCell.textContent.toLowerCase();
            row.style.display = name.includes(query) ? '' : 'none';
        });
    });

    // Auto-hide success message after 5 seconds
    const alertBox = document.querySelector('.alert-success');
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 5000);
    }
</script>
</body>
</html>
