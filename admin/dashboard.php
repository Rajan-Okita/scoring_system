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
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; background-color: #f0f2f5; }
        h2, h3 { text-align: center; }
        form, table { max-width: 700px; margin: 20px auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 6px; font-size: 16px; }
        button { background-color: #007BFF; color: white; border: none; cursor: pointer; transition: background 0.3s; }
        button:hover { background-color: #0056b3; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        table th, table td { border: 1px solid #ccc; padding: 12px; text-align: center; }
        table th { background-color: #f8f8f8; }
        .message { color: green; text-align: center; margin-top: 15px; font-weight: bold; }
    </style>
</head>
<body>

<h2>Admin Dashboard</h2>
<?php if (!empty($message)): ?>
    <div class="message"><?= $message ?></div>
<?php endif; ?>

<form method="POST">
    <h3>Add New Judge</h3>
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="last_name" placeholder="Last Name" required>
    <input type="email" name="email" placeholder="Email Address" required>
    <input type="text" name="display_name" placeholder="Display Name" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Add Judge</button>
</form>

<h3>Existing Judges</h3>
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Display Name</th>
    </tr>
    <?php while ($judge = $judges->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($judge['first_name'] . ' ' . $judge['last_name']) ?></td>
            <td><?= htmlspecialchars($judge['email_address']) ?></td>
            <td><?= htmlspecialchars($judge['display_name']) ?></td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>

