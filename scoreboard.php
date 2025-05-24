<?php
include './auth/connection.php';
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
            xhr.open('GET', 'search_users.php?search=' + encodeURIComponent(value), true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('table-body').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        window.onload = function () {
            const input = document.getElementById('search');
            input.addEventListener('keyup', function () {
                searchUsers(this.value);
            });

            searchUsers('');
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
