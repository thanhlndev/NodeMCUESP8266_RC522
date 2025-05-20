<?php
// Ghi log dữ liệu nhận được
file_put_contents('post_log.txt', date('Y-m-d H:i:s') . " - " . print_r($_POST, true) . "\n", FILE_APPEND);

// Validate đầu vào
$uidResult = isset($_POST['UIDresult']) ? trim($_POST['UIDresult']) : '';
$location = isset($_POST['location']) ? trim($_POST['location']) : 'Home1'; // Mặc định Home1 nếu không có
if ($uidResult === '') {
    file_put_contents('post_log.txt', date('Y-m-d H:i:s') . " - No UID provided\n", FILE_APPEND);
    echo "No UID received";
    exit;
}

// Kết nối database
require 'database.php';
$pdo = Database::connect();

// Kiểm tra và cập nhật hoặc chèn dữ liệu
$sqlCheck = "SELECT id FROM table_nodemcu_rfidrc522_mysql WHERE id = :id";
$queryCheck = $pdo->prepare($sqlCheck);
$queryCheck->execute(['id' => $uidResult]);
if ($queryCheck->fetch()) {
    // Cập nhật location nếu đã tồn tại
    $sqlUpdate = "UPDATE table_nodemcu_rfidrc522_mysql SET location = :location WHERE id = :id";
    $queryUpdate = $pdo->prepare($sqlUpdate);
    $queryUpdate->execute(['id' => $uidResult, 'location' => $location]);
} else {
    // Chèn mới nếu không tồn tại (chỉ cập nhật location khi đăng ký)
    $sqlInsert = "INSERT INTO table_nodemcu_rfidrc522_mysql (id, location) VALUES (:id, :location)";
    $queryInsert = $pdo->prepare($sqlInsert);
    $queryInsert->execute(['id' => $uidResult, 'location' => $location]);
}
Database::disconnect();

// Lưu UID vào UIDContainer.php
$write = "<?php \$UIDresult = '" . htmlspecialchars($uidResult, ENT_QUOTES, 'UTF-8') . "'; echo \$UIDresult; ?>";
if (file_put_contents('UIDContainer.php', $write) === false) {
    file_put_contents('post_log.txt', date('Y-m-d H:i:s') . " - Failed to write UIDContainer.php\n", FILE_APPEND);
    echo "Failed to save UID";
    exit;
}

echo "UID received: $uidResult, Location: $location";
