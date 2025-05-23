<?php
$write = "<?php \$UIDresult = ''; echo \$UIDresult; ?>";
file_put_contents('UIDContainer.php', $write);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | NodeMCU RFID System</title>
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

        img {
            max-width: 55%;
            margin: 20px auto;
            display: block;
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
            <a class="active" href="home.php">Home</a>
            <a href="user_data.php">User Data</a>
            <a href="registration.php">Registration</a>
            <a href="read_tag.php">Read Tag ID</a>
        </nav>
    </div>
    <div class="content">
        <h3 class="text-center mb-4">Welcome to NodeMCU RFID System</h3>
        <div class="row">
            <div class="col-md-6">
                <img src="home_ok_ok.jpg" alt="Home Image" class="img-fluid">
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">System Information</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fas fa-microchip"></i> Hardware: NodeMCU V3 ESP8266
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-id-card"></i> RFID Reader: RC522
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-database"></i> Database: MySQL
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-server"></i> Server: PHP
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title">Quick Links</h5>
                        <div class="d-grid gap-2">
                            <a href="user_data.php" class="btn btn-primary">
                                <i class="fas fa-users"></i> View Users
                            </a>
                            <a href="registration.php" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> Register New User
                            </a>
                            <a href="read_tag.php" class="btn btn-info">
                                <i class="fas fa-id-card"></i> Read Tag
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdlZxGkvSEj7mgsX" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
</body>

</html>