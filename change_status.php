<?php
// Подключение к базе данных
include 'db_connect.php';

// Проверка, был ли передан параметр id через URL
if (isset($_GET['request_id'])) {
    $request_id = $_GET['request_id'];

    // Выполнение SQL-запроса для обновления статуса и даты
    $sql = "UPDATE repair_requests SET status = 'готово', update_at = NOW() WHERE request_id = $request_id";

    if ($conn->query($sql) === TRUE) {
        echo "Статус успешно обновлен на 'готово'.";
    } else {
        echo "Ошибка при обновлении статуса: " . $conn->error;
    }
} else {
    echo "Не передан идентификатор заявки.";
}

// Закрытие соединения с базой данных
$conn->close();
?>
