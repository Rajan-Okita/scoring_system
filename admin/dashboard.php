<?php
include '../auth/connection.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $display_name = trim($_POST['display_name']);
    $password = trim($_POST['password']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $con->prepare("INSERT INTO judges (first_name, last_name, email_address, display_name, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $display_name, $hashed_password);

    if ($stmt->execute()) {
        $message = "✅ Judge added successfully.";
    } else {
        $message = "❌ Error: " . $stmt->error;
    }
}

$judges = $con->query("SELECT * FROM judges");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f8;
            padding-top: 80px;
        }
        .card {
            margin-bottom: 30px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        .message {
            color: green;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">Admin Dashboard</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <ul class="nav nav-tabs justify-content-center mb-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="add-tab" data-bs-toggle="tab" data-bs-target="#add" type="button" role="tab">➕ Add Judge</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="view-tab" data-bs-toggle="tab" data-bs-target="#view" type="button" role="tab">👥 View Judges</button>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Add Judge -->
        <div class="tab-pane fade show active" id="add" role="tabpanel">
            <div class="card">
                <div class="card-header bg-primary text-white">Add New Judge</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="display_name" class="form-control" placeholder="Display Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Judge</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Judges -->
        <div class="tab-pane fade" id="view" role="tabpanel">
            <div class="card">
                <div class="card-header bg-secondary text-white">Existing Judges</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Display Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while ($judge = $judges->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($judge['first_name'] . ' ' . $judge['last_name']) ?></td>
                                    <td><?= htmlspecialchars($judge['email_address']) ?></td>
                                    <td><?= htmlspecialchars($judge['display_name']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
