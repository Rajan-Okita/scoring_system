<?php
include './auth/connection.php';

// Handle live search
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $searchTerm = "%" . $search . "%";

    $stmt = $con->prepare("SELECT name, score FROM users WHERE name LIKE ? ORDER BY score DESC");
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['score']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='2' class='text-center'>No matching users found.</td></tr>";
    }
    exit;
}

include 'navbar.php';

$limit = 10;
$page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
$offset = ($page - 1) * $limit;

// Get total users
$totalResult = $con->query("SELECT COUNT(*) AS total FROM users");
$totalUsers = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalUsers / $limit);

// Fetch paginated users
$stmt = $con->prepare("SELECT name, score FROM users ORDER BY score DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scoreboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="scoreboard-container">
    <h2>🏆 Participant Scoreboard</h2>

    <form class="scoreboard-search" onsubmit="return false;">
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
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['score']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="pagination-wrapper">
        <nav>
            <ul class="pagination">
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>

<script>
    const searchInput = document.getElementById('search');
    const tableBody = document.getElementById('table-body');
    const pagination = document.querySelector('.pagination-wrapper');

    searchInput.addEventListener('input', function () {
        const value = this.value.trim();
        if (value === '') {
            location.href = 'scoreboard.php'; // return default pagination
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'scoreboard.php?search=' + encodeURIComponent(value), true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                tableBody.innerHTML = xhr.responseText;
                pagination.style.display = 'none';
            }
        };
        xhr.send();
    });
</script>

</body>
</html>
