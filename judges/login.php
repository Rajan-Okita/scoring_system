<?php
include '../auth/connection.php';
include 'judges_navbar.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $con->prepare("SELECT * FROM judges WHERE email_address = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($judge = $result->fetch_assoc()) {
        if (password_verify($password, $judge['password'])) {
            header("Location: dashboard.php");
            exit();
        } else {
            $message = "❌ Incorrect password.";
        }
    } else {
        $message = "❌ Judge not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Judge Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 80px;
            margin: 0;
            background:
                    linear-gradient(135deg, rgba(30, 60, 114, 0.8), rgba(109, 158, 255, 0.8)),
                    url("https://images.pexels.com/photos/7233099/pexels-photo-7233099.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2");
            background-attachment: fixed;
            background-size: cover;
        }
        .login-box {
            max-width: 600px;
            margin: 100px auto 40px;
            padding: 30px 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }
        .message {
            margin-top: 15px;
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="login-box">
        <h3 class="text-center mb-4">🧑‍⚖️ Judge Login</h3>
        <form method="POST">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Login</button>
        </form>
        <?php if (!empty($message)): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
