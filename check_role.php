<?php
// Подключение к базе данных (замените на ваши данные)
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "AutoService";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Проверка авторизации пользователя
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $role = ''; // Получите роль пользователя из базы данных

    if ($role === 'customer') {
        header("Location: index.php"); // Перенаправление на index.php
    } elseif ($role === 'admin' || $role === 'technician') {
        header("Location: view_requests.php?role=admin");
    }
} else {
    echo "Пользователь не авторизован.";
}
?>