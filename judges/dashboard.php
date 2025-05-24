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
<html>
<head>
    <title>Judge Dashboard</title>
    <style>
        body { font-family: Arial; margin: 0; background-color: #f0f2f5; display: flex; }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            height: 100vh;
            position: fixed;
        }
        .sidebar a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .main-content {
            margin-left: 250px;
            padding: 40px;
            width: calc(100% - 250px);
        }
        h2, h3 { text-align: center; }
        .message { color: green; text-align: center; margin-top: 15px; font-weight: bold; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); margin-bottom: 30px; }
        input, button { padding: 10px; margin: 5px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px; }
        button { background-color: #007bff; color: white; border: none; cursor: pointer; transition: background 0.3s; }
        button:hover { background-color: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        th { background-color: #f8f8f8; }
        form.inline-form { display: flex; justify-content: center; align-items: center; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2 style="text-align:center;">Judge Panel</h2>
    <a href="#">👥 View & Edit Scores</a>
</div>

<div class="main-content">
    <?php if ($showMessage): ?>
        <div class="message"> <?= $message ?> </div>
    <?php endif; ?>

    <div class="card">
        <h3>Participants and Their Scores</h3>
        <table>
            <tr>
                <th>Participant</th>
                <th>Current Score</th>
                <th>Update Score</th>
            </tr>
            <?php while ($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= $user['score'] ?></td>
                    <td>
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="user_id" value="<?= $user['users_id'] ?>">
                            <input type="number" name="points" min="0" max="100" value="<?= $user['score'] ?>" required>
                            <button type="submit" name="update_score">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

</body>
</html>
