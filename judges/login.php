<?php
include '../auth/connection.php';
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
            $message = "Incorrect password.";
        }
    } else {
        $message = "judge not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Judge Login</title>
    <style>
        body { font-family: Arial; padding: 20px; text-align: center; }
        form { max-width: 400px; margin: auto; }
        input, button { width: 100%; padding: 10px; margin: 10px 0; }
        .message { color: red; margin-top: 10px; }
    </style>
</head>
<body>
<h2>Judge Login</h2>
<form method="POST">
    <input type="email" name="email" placeholder="Email Address" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
<div class="message"><?= $message ?></div>
</body>
</html>