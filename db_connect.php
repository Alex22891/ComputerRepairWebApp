<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "AutoService";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}
?>
