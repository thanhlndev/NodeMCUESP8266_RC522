<?php
require 'database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? trim($_POST['id']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : 'Home1';

    if ($id && $name && $gender && $email && $mobile) {
        $pdo = Database::connect();
        $sql = "INSERT INTO table_nodemcu_rfidrc522_mysql (id, name, gender, email, mobile, location) VALUES (:id, :name, :gender, :email, :mobile, :location)";
        $query = $pdo->prepare($sql);
        $query->execute([
            'id' => $id,
            'name' => $name,
            'gender' => $gender,
            'email' => $email,
            'mobile' => $mobile,
            'location' => $location
        ]);
        Database::disconnect();
        header("Location: user_data.php");
        exit;
    }
}
header("Location: registration.php");
