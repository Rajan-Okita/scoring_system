<?php
include '../auth/connection.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $display_name = trim($_POST['display_name']);
    $password = trim($_POST['password']);

    // Check for duplicate email
    $check = $con->prepare("SELECT judges_id FROM judges WHERE email_address = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $message = "❌ A judge with this email already exists.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $con->prepare("INSERT INTO judges (first_name, last_name, email_address, display_name, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $display_name, $hashed_password);

        if ($stmt->execute()) {
            $message = "✅ Judge added successfully.";
        } else {
            $message = "❌ Error: " . $stmt->error;
        }
    }
}

// Fetch all judges for display
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
            padding-top: 56px;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px 20px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
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

<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Panel</a>
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
            <a href="#" onclick="showTab('add')">➕ Add Judge</a>
            <a href="#" onclick="showTab('view')">👥 View Judges</a>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="pt-4">
                <h2 class="text-center mb-4">Admin Dashboard</h2>

                <?php if (!empty($message)): ?>
                    <div class="message alert alert-info" id="feedbackMessage"><?= $message ?></div>
                <?php endif; ?>

                <div class="tab-content">
                    <!-- Add Judge -->
                    <div class="tab-pane fade show active" id="add">
                        <div class="card">
                            <div class="card-header bg-primary text-white">Add New Judge</div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="mb-3"><input type="text" name="first_name" class="form-control" placeholder="First Name" required></div>
                                    <div class="mb-3"><input type="text" name="last_name" class="form-control" placeholder="Last Name" required></div>
                                    <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email Address" required></div>
                                    <div class="mb-3"><input type="text" name="display_name" class="form-control" placeholder="Display Name" required></div>
                                    <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                                    <button type="submit" class="btn btn-primary w-100">Add Judge</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- View Judges -->
                    <div class="tab-pane fade" id="view">
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
        </main>
    </div>
</div>

<script>
    function showTab(tabId) {
        document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('show', 'active'));
        document.getElementById(tabId).classList.add('show', 'active');
    }

    // Auto-hide message after 5 seconds
    const msg = document.getElementById('feedbackMessage');
    if (msg) {
        setTimeout(() => {
            msg.style.display = 'none';
        }, 5000);
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
