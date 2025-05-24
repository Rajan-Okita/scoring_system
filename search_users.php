<?php
include './auth/connection.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchTerm = "%" . $search . "%";

$stmt = $con->prepare("SELECT name, score FROM users WHERE name LIKE ? ORDER BY score DESC");
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= $row['score'] ?></td>
    </tr>
<?php endwhile; ?>
