<?php
include './auth/connection.php';

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $searchTerm = "%" . $search . "%";

    $stmt = $con->prepare("SELECT name, score FROM users WHERE name LIKE ? ORDER BY score DESC");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['score']) . "</td>";
        echo "</tr>";
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Scoreboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 40px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; margin-bottom: 20px; }
        form { text-align: center; margin-bottom: 20px; }
        input[type="text"] {
            padding: 10px;
            width: 60%;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f1f1f1; }
    </style>
    <script>
        function searchUsers(value) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'scoreboard.php?search=' + encodeURIComponent(value), true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('table-body').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        function pollUpdates() {
            fetch('long_poll.php?last=' + (window.lastUpdate || 0))
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'update') {
                        window.lastUpdate = data.last;
                        searchUsers(document.getElementById('search').value);
                    }
                    pollUpdates();
                })
                .catch(() => {
                    setTimeout(pollUpdates, 1000);
                });
        }

        window.onload = function () {
            const input = document.getElementById('search');
            input.addEventListener('keyup', function () {
                searchUsers(this.value);
            });
            searchUsers('');
            pollUpdates();
        };
    </script>
</head>
<body>
<div class="container">
    <h2>🏆 Participant Scoreboard</h2>
    <form onsubmit="return false;">
        <input type="text" id="search" placeholder="Search by participant name">
    </form>

    <table>
        <thead>
        <tr>
            <th>Participant Name</th>
            <th>Score</th>
        </tr>
        </thead>
        <tbody id="table-body">
        </tbody>
    </table>
</div>
</body>
</html>
