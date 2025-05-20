<?php
require 'database.php';

$id = isset($_GET['id']) ? trim($_GET['id']) : '';
if ($id === '') {
    echo "<h4 class='text-danger'>No ID provided.</h4>";
    exit;
}

$pdo = Database::connect();
$sql = "SELECT * FROM table_nodemcu_rfidrc522_mysql WHERE id = :id";
$query = $pdo->prepare($sql);
$query->execute(['id' => $id]);
$row = $query->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo "<div class='container'><h3 class='text-center mb-3'>User Data</h3>";
    echo "<table class='table table-striped table-bordered'>";
    echo "<tr><th>ID</th><td>" . htmlspecialchars($row['id']) . "</td></tr>";
    echo "<tr><th>Name</th><td>" . (isset($row['name']) ? htmlspecialchars($row['name']) : 'Not registered') . "</td></tr>";
    echo "<tr><th>Gender</th><td>" . (isset($row['gender']) ? htmlspecialchars($row['gender']) : 'Not registered') . "</td></tr>";
    echo "<tr><th>Email</th><td>" . (isset($row['email']) ? htmlspecialchars($row['email']) : 'Not registered') . "</td></tr>";
    echo "<tr><th>Mobile Number</th><td>" . (isset($row['mobile']) ? htmlspecialchars($row['mobile']) : 'Not registered') . "</td></tr>";
    echo "<tr><th>Location</th><td>" . (isset($row['location']) ? htmlspecialchars($row['location']) : 'Not set') . "</td></tr>";
    echo "</table></div>";
} else {
    echo "<div class='container'><h3 class='text-center mb-3'>The ID of your Card / KeyChain is not registered!</h3>";
    echo "<h4 class='text-center'>The ID of your Card / KeyChain is:</h4>";
    echo "<h3 class='text-center'>" . htmlspecialchars($id) . "</h3></div>";
}

Database::disconnect();
