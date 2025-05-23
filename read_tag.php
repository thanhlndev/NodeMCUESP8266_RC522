<?php
require 'database.php';

// Lấy UID từ UIDContainer.php
$uid = '';
if (file_exists('UIDContainer.php')) {
    ob_start();
    include 'UIDContainer.php';
    $uid = ob_get_clean();
    $uid = trim($uid); // Loại bỏ khoảng trắng thừa
}

// Debug log
$logMessage = date('Y-m-d H:i:s') . " - Initial UID: " . $uid . "\n";
file_put_contents('post_log.txt', $logMessage, FILE_APPEND);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read Tag | NodeMCU RFID System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
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
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: opacity 0.3s ease;
        }

        #userDataContent {
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        #userDataContent.fade {
            opacity: 0.5;
            /* Hiệu ứng mờ nhẹ khi dữ liệu đang cập nhật */
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
            <a href="user_data.php">User Data</a>
            <a href="registration.php">Registration</a>
            <a class="active" href="read_tag.php">Read Tag ID</a>
        </nav>
    </div>
    <div class="content">
        <h3 class="text-center mb-4">Please Tag to Display ID or User Data</h3>
        <div id="getUID" style="display: none;"></div>
        <div id="show_user_data" class="text-center">
            <div id="userDataContent">
                <?php if ($uid && $uid !== ''): ?>
                    <h4>UID: <?php echo htmlspecialchars($uid); ?></h4>
                    <p>Checking user data...</p>
                <?php else: ?>
                    <h4 class="text-muted">Waiting for RFID card...</h4>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(() => {
            let currentUID = '<?php echo addslashes($uid); ?>';
            let lastData = '';
            let isProcessing = false;
            let lastUpdateTime = 0;

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            function updateDisplay() {
                if (isProcessing || (Date.now() - lastUpdateTime < 500)) return;
                isProcessing = true;
                lastUpdateTime = Date.now();
                $('#userDataContent').addClass('fade'); // Hiệu ứng mờ nhẹ khi cập nhật
                console.log("Updating display at:", new Date().toISOString());

                // Lấy UID từ UIDContainer.php
                $.ajax({
                    url: "UIDContainer.php?t=" + new Date().getTime(),
                    method: "GET",
                    cache: false,
                    headers: {
                        "Cache-Control": "no-cache, no-store, must-revalidate"
                    },
                    success: (response) => {
                        const uid = response.trim();
                        console.log("Fetched UID:", uid, "Previous UID:", currentUID);

                        if (!uid || uid === '') {
                            if (currentUID !== '') {
                                $("#userDataContent").html("<h4 class='text-muted'>Waiting for RFID card...</h4>");
                                currentUID = '';
                                lastData = '';
                            }
                        } else {
                            currentUID = uid;
                            fetchUserData(currentUID);
                        }
                    },
                    error: (xhr, status, error) => {
                        console.error("Error loading UID:", error, "Status:", status);
                        $("#userDataContent").html(
                            "<div class='alert alert-danger'>" +
                            "<h4>Error loading UID</h4>" +
                            "<p>" + error + " (Status: " + status + ")</p>" +
                            "</div>"
                        );
                    },
                    complete: () => {
                        $('#userDataContent').removeClass('fade');
                        isProcessing = false;
                    }
                });
            }

            function fetchUserData(uid) {
                console.log("Fetching user data for UID:", uid);
                $.ajax({
                    url: "read_tag_user_data.php?t=" + new Date().getTime(),
                    method: "GET",
                    data: {
                        id: uid
                    },
                    cache: false,
                    headers: {
                        "Cache-Control": "no-cache, no-store, must-revalidate"
                    },
                    success: (data) => {
                        console.log("Fetched user data:", data);
                        if (data !== lastData) {
                            $("#userDataContent").html(
                                "<h4>UID: " + currentUID + "</h4>" +
                                data
                            );
                            lastData = data;
                        }
                    },
                    error: (xhr, status, error) => {
                        console.error("Error fetching user data:", error, "Status:", status);
                        $("#userDataContent").html(
                            "<div class='alert alert-danger'>" +
                            "<h4>Error fetching user data</h4>" +
                            "<p>" + error + " (Status: " + status + ")</p>" +
                            "</div>"
                        );
                    },
                    complete: () => {
                        $('#userDataContent').removeClass('fade');
                        isProcessing = false;
                    }
                });
            }

            // Gọi ngay khi tải trang
            updateDisplay();
            // Cập nhật mỗi 500ms với debounce
            const debouncedUpdate = debounce(updateDisplay, 100);
            setInterval(debouncedUpdate, 500);
        });
    </script>
</body>

</html>