<?php
include './auth/connection.php';

// Allow browser to wait up to 1 second
set_time_limit(1);

// Get the latest known timestamp from the client
$lastUpdate = isset($_GET['last']) ? $_GET['last'] : 0;
$start = time();

while ((time() - $start) < 1) { // 1 second timeout window
    $query = "SELECT MAX(updated_at) as latest FROM users";
    $result = $con->query($query);
    $row = $result->fetch_assoc();
    $latestUpdate = strtotime($row['latest']);

    if ($latestUpdate > $lastUpdate) {
        echo json_encode([
            'status' => 'update',
            'last' => $latestUpdate
        ]);
        exit;
    }

    usleep(500000); // 0.5second delay before rechecking
}

echo json_encode(['status' => 'no_update', 'last' => $lastUpdate]);

