<?php
require 'database.php';

file_put_contents('post_log.txt', date('Y-m-d H:i:s') . " - " . print_r($_POST, true) . "\n", FILE_APPEND);
$uidResult = isset($_POST['UIDresult']) ? $_POST['UIDresult'] : '';
$location = isset($_POST['location']) ? $_POST['location'] : 'Unknown';

if ($uidResult) {
    // Cập nhật location vào database
    $pdo = Database::connect();
    $sql = "UPDATE table_nodemcu_rfidrc522_mysql SET location = :location WHERE id = :uid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':uid', $uidResult);
    $stmt->execute();
    Database::disconnect();

    // Lưu UID và location vào UIDContainer.php
    $write = "<?php \$UIDresult = '" . htmlspecialchars($uidResult, ENT_QUOTES, 'UTF-8') . "'; " .
        "\$location = '" . htmlspecialchars($location, ENT_QUOTES, 'UTF-8') . "'; " .
        "echo \$UIDresult; ?>";
    file_put_contents('UIDContainer.php', $write);
    echo "UID received: $uidResult, Location: $location";
} else {
    echo "No UID received";
}
