<!DOCTYPE html>
<html>
<head>
    <title>Judging System Home</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { text-align: center; background: white; padding: 50px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 30px; font-size: 32px; }
        a.button {
            display: inline-block;
            padding: 15px 30px;
            margin: 10px;
            font-size: 18px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border-radius: 6px;
            transition: background 0.3s;
        }
        a.button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Welcome to the Judging System</h1>
    <a class="button" href="admin/login.php">🔐 Admin Portal</a>
    <a class="button" href="judges/login.php">🧑‍⚖️ Judge Panel</a>
    <a class="button" href="scoreboard.php">📊 View Scoreboard</a>
</div>
</body>
</html>
