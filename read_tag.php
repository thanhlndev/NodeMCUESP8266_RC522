<?php
require 'database.php';
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
        <div id="show_user_data" class="text-center"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdlZxGkvSEj7mgsX" crossorigin="anonymous"></script>
    <script>
        $(document).ready(() => {
            let currentUID = '';
            let lastData = '';

            function updateDisplay() {
                // Lấy UID từ UIDContainer.php
                $("#getUID").load("UIDContainer.php", () => {
                    const uid = $("#getUID").text().trim();
                    console.log("UID loaded: " + uid);

                    if (uid === '') {
                        $("#show_user_data").html("<h4 class='text-muted'>Waiting for RFID card...</h4>");
                    } else if (uid !== currentUID) {
                        currentUID = uid;
                        console.log("New UID detected: " + currentUID);
                        fetchUserData(currentUID);
                    } else if (currentUID) {
                        // Kiểm tra dữ liệu mới định kỳ
                        fetchUserData(currentUID);
                    }
                });
            }

            function fetchUserData(uid) {
                $.get("read_tag_user_data.php?id=" + encodeURIComponent(uid), (data) => {
                    console.log("Received data: " + data);
                    if (data !== lastData) {
                        $("#show_user_data").html(data);
                        lastData = data;
                    }
                }).fail((xhr, status, error) => {
                    console.error("Failed to fetch user data: " + error);
                    $("#show_user_data").html("<h4 class='text-danger'>Error fetching user data: " + error + "</h4>");
                });
            }

            // Gọi ngay khi tải trang
            updateDisplay();
            // Cập nhật mỗi 500ms
            setInterval(updateDisplay, 500);
        });
    </script>
</body>

</html>