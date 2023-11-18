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

// Проверка, была ли отправлена форма
if (isset($_POST['submit_request'])) {
    // Получение данных из формы
    $description = $_POST['description'];
    $customer_id = $_POST['customer_id']; // Получаем customer_id из формы
    $status = "ожидание"; // Устанавливаем начальный статус заявки
    $created_at = date("Y-m-d H:i:s"); // Текущая дата и время

    // SQL-запрос для добавления заявки в базу данных
    $sql = "INSERT INTO repair_requests (customer_id, description, status, created_at) VALUES ('$customer_id', '$description', '$status', '$created_at')";

    if ($conn->query($sql) === TRUE) {
        echo "Заявка успешно отправлена.";
    } else {
        echo "Ошибка при отправке заявки: " . $conn->error;
    }
}

// Закрытие соединения с базой данных
$conn->close();
?>