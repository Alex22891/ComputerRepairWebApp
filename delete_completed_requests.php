<?php
// Подключение к базе данных
include 'db_connect.php';

// Проверка, была ли отправлена форма удаления готовых заказов
if (isset($_POST['delete_completed'])) {
    $sql = "DELETE FROM repair_requests WHERE status = 'готово'";

    if ($conn->query($sql) === TRUE) {
        echo "Готовые заказы успешно удалены.";
    } else {
        echo "Ошибка при удалении готовых заказов: " . $conn->error;
    }
}

// Закрытие соединения с базой данных
$conn->close();
?>
