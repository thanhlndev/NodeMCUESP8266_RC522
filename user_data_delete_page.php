<?php
$write = "<?php \$UIDresult = ''; echo \$UIDresult; ?>";
file_put_contents('UIDContainer.php', $write);
require 'database.php';
$id = isset($_GET['id']) ? $_GET['id'] : '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = Database::connect();
    $sql = "DELETE FROM table_nodemcu_rfidrc522_mysql WHERE id = :id";
    $query = $pdo->prepare($sql);
    $query->execute(['id' => $id]);
    Database::disconnect();
    header("Location: user_data.php");
    exit;
}
$pdo = Database::connect();
$sql = "SELECT * FROM table_nodemcu_rfidrc522_mysql WHERE id = :id";
$query = $pdo->prepare($sql);
$query->execute(['id' => $id]);
$data = $query->fetch(PDO::FETCH_ASSOC);
Database::disconnect();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User | NodeMCU RFID System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .topnav {
            background-color: #28a745;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .topnav a {
            color: white;
            padding: 14px 16px;
            text-decoration: none;
            display: inline-block;
        }

        .topnav a:hover {
            background-color: #218838;
        }

        .topnav a.active {
            background-color: #1e7e34;
        }

        .content {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .topnav a {
                display: block;
                width: 100%;
                text-align: center;
            }

            .content {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center mb-4">NodeMCU V3 ESP8266 with MySQL Database</h2>
        <nav class="topnav">
            <a href="home.php">Home</a>
            <a class="active" href="user_data.php">User Data</a>
            <a href="registration.php">Registration</a>
            <a href="read_tag.php">Read Tag ID</a>
        </nav>
    </div>
    <div class="content">
        <h3 class="text-center mb-4">Confirm Delete User</h3>
        <?php if ($data): ?>
            <p>Are you sure you want to delete the user with ID: <?php echo htmlspecialchars($data['id']); ?>?</p>
            <form method="post">
                <button type="submit" class="btn btn-danger me-2">Yes</button>
                <a href="user_data.php" class="btn btn-secondary">No</a>
            </form>
        <?php else: ?>
            <p>User not found.</p>
            <a href="user_data.php" class="btn btn-primary">Back</a>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdlZxGkvSEj7mgsX" crossorigin="anonymous"></script>
</body>

</html>