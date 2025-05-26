<?php
include './auth/connection.php';
include 'navbar.php';

if (isset($_GET['search'])) {
    $limit =10;//entries per page
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;
    $search = trim($_GET['search']);
    $searchTerm = "%" . $search . "%";

    // Count total results
    $countStmt = $con->prepare("SELECT COUNT(*) as total FROM users WHERE name LIKE ?");
    $countStmt->bind_param("s", $searchTerm);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $totalRows = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($totalRows / $limit);

    // Fetch paginated data
    $stmt = $con->prepare("SELECT name, score FROM users WHERE name LIKE ? ORDER BY score DESC LIMIT ?, ?");
    $stmt->bind_param("sii", $searchTerm, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['score']) . "</td>";
        echo "</tr>";
    }

    // Pagination buttons
    echo "<tr><td colspan='2'>";
    echo "<div class='pagination'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = ($i == $page) ? 'active-page' : '';
        echo "<button class='page-btn $active' onclick='goToPage($i)'>$i</button> ";
    }
    echo "</div>";
    echo "</td></tr>";

    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Scoreboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="scoreboard-container">
    <h2>🏆 Participant Scoreboard</h2>
    <form onsubmit="return false;">
        <input type="text" id="search" placeholder="Search by participant name">
    </form>

    <table class="scoreboard-table" id="main-table">
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

<script>
    let currentPage = 1;

    function searchUsers(value, page = 1) {
        currentPage = page;
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'scoreboard.php?search=' + encodeURIComponent(value) + '&page=' + page, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                document.getElementById('table-body').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

    function goToPage(page) {
        const value = document.getElementById('search').value;
        searchUsers(value, page);
    }

   //Long polling function
    function pollUpdates() {
        fetch('long_poll.php?last=' + (window.lastUpdate || 0))
            .then(response => response.json())
            .then(data => {
                if (data.status === 'update') {
                    window.lastUpdate = data.last;
                    searchUsers(document.getElementById('search').value, currentPage);
                }
                pollUpdates();
            })
            .catch(() => {
                setTimeout(pollUpdates, 5000);
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
</body>
</html>
