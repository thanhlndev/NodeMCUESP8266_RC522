<?php
require 'database.php';

$uid = isset($_GET['id']) ? $_GET['id'] : '';
if ($uid) {
    $pdo = Database::connect();
    $sql = "SELECT name, gender, email, mobile, location FROM table_nodemcu_rfidrc522_mysql WHERE id = :uid LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':uid', $uid);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();

    if ($row) {
        echo "<table class='table table-striped'>";
        echo "<tr><th>Name</th><td>" . htmlspecialchars($row['name']) . "</td></tr>";
        echo "<tr><th>Gender</th><td>" . htmlspecialchars($row['gender']) . "</td></tr>";
        echo "<tr><th>Email</th><td>" . htmlspecialchars($row['email']) . "</td></tr>";
        echo "<tr><th>Mobile</th><td>" . htmlspecialchars($row['mobile']) . "</td></tr>";
        echo "<tr><th>Location</th><td>" . htmlspecialchars($row['location']) . "</td></tr>";
        echo "</table>";
    } else {
        echo "<p class='text-warning'>No user data found for UID: " . htmlspecialchars($uid) . "</p>";
    }
} else {
    echo "<p class='text-danger'>Invalid UID</p>";
}
