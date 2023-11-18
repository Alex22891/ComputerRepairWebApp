<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Автомастерская</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        h2 {
            color: #0074d9;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #0074d9;
            color: #fff;
        }

        td {
            background-color: #f9f9f9;
        }

        .actions {
            text-align: center;
        }

        .actions a {
            text-decoration: none;
            color: #0074d9;
            margin: 0 5px;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .delete-button {
            background-color: #ff4136;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #d0021b;
        }
    </style>
</head>
<body>
    <div class="container">
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

        // Проверка роли, которая передается через параметры URL
        if (isset($_GET['role'])) {
            $role = $_GET['role'];

            if ($role === 'customer') {
                // Отобразите форму подачи заявки на ремонт для роли "customer"
                ?>
                <h2>Подать заявку на ремонт</h2>
                <form method="post" action="submit_request.php">
                    <label for="customer">Выберите заказчика:</label>
                    <select name="customer_id" id="customer_id" required>
                        <?php
                        // Получите список пользователей с ролью "customer" из базы данных
                        $sql = "SELECT * FROM customers";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Используйте значение ID пользователя в качестве значения <option>, чтобы его можно было легко идентифицировать при отправке формы
                                echo "<option value='" . $row["customer_id"] . "'>" . $row["first_name"] . " " . $row["last_name"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                    <br>
                    <br>
                    <label for="description">Описание проблемы:</label>
                    <textarea name="description" id="description" required></textarea>
                    <br>
                    <br>
                    <button type="submit" name="submit_request">Отправить заявку</button>
                </form>
                <?php
            } elseif ($role === 'administrator') {
                // Отобразите список заявок и управление для роли "admin"
                ?>
                <h2>Список заявок:</h2>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Описание</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                    <?php
                    $sql = "SELECT * FROM repair_requests";
                    $result = $conn->query($sql);

                    if ($result === false) {
                        die("Ошибка запроса: " . $conn->error);
                    }

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["request_id"] . "</td>";
                            echo "<td>" . $row["description"] . "</td>";
                            echo "<td>" . $row["status"] . "</td>";
                            echo "<td class='actions'>";
                            echo "<a href='change_status.php?request_id=" . $row["request_id"] . "'>Изменить статус</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "Нет заявок.";
                    }
                    ?>
                </table>
                <br>
                <form method="post" action="delete_completed_requests.php">
                    <button class="delete_button" type="submit" name="delete_completed">Удалить готовые заказы</button>
                </form>
                <?php
            } else {
                echo "Неверная роль пользователя.";
            }
        } else {
            echo "Не указана роль пользователя.";
        }
        ?>
    </div>
</body>
</html>