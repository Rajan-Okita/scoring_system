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
            padding-top: 50px;
        }
        .card {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-center">🧑‍⚖️ Judge Dashboard</h2>

    <?php if ($showMessage): ?>
        <div class="alert alert-success text-center"><?= $message ?></div>
    <?php endif; ?>

    <div class="card p-4">
        <h5 class="mb-3 text-center">Participants and Their Scores</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
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

</body>
</html>
