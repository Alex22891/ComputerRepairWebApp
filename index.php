<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Авторизация и регистрация</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('./background.jpg') center/cover no-repeat; /* Add your background image URL */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        h2 {
            text-align: center;
        }

        .form-container {
            max-width: 400px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Add background color with transparency */
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container form {
            text-align: center;
        }

        .form-container input {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #0074d9;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .toggle-button {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container" id="login-form">
        <h2>Авторизация</h2>
        <form method="post" class="login-form">
            <input type="text" name="username" placeholder="Имя пользователя" required>
            <input type="password" name = "password" placeholder="Пароль" required>
            <button type="submit" name="login">Войти</button>
        </form>
        <div class="toggle-button">
            <button id="show-registration-form">Зарегистрироваться</button>
        </div>
    </div>

    <div class="form-container" id="registration-form" style="display: none;">
        <h2>Регистрация</h2>
        <form method="post" class="registration-form">
            <input type="text" name="username" placeholder="Имя пользователя" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <input type="text" name="full_name" placeholder="ФИО" required>
            <input type="text" name="email" placeholder="Email" required>
            <input type="text" name="phone_number" placeholder="Номер телефона" required>
            <button type="submit" name="register">Зарегистрироваться</button>
        </form>
        <div class="toggle-button">
            <button id="show-login-form">Войти</button>
        </div>
    </div>

    <script>
        // JavaScript to toggle between login and registration forms
        document.getElementById('show-registration-form').addEventListener('click', function() {
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('registration-form').style.display = 'block';
        });

        document.getElementById('show-login-form').addEventListener('click', function() {
            document.getElementById('registration-form').style.display = 'none';
            document.getElementById('login-form').style.display = 'block';
        });
    </script>
</body>
</html>
<?
// Подключение к базе данных
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "AutoService";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Авторизация
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Авторизация успешна, выполните действия для авторизованных пользователей
        session_start();
        setcookie("username", $username, time()+3600);
        $_SESSION['username'] = $username;

        // Получите роль пользователя из базы данных
        $row = $result->fetch_assoc();
        $role = $row['role'];

        if ($role === 'customer') {
            $_SESSION['customer_id'] = $row['customer_id'];
            header("Location: view_requests.php?role=$role"); // Перенаправление на страницу для роли "customer"
        } elseif ($role === 'administrator' || $role === 'technician') {
            header("Location: view_requests.php?role=$role"); // Перенаправление на страницу для роли "admin" или "technician"
        }
    } else {
        echo "Неправильное имя пользователя или пароль.";
    }
}

// Регистрация
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'customer';
    $full_name = isset($_POST['full_name']) ? $_POST['full_name'] : '';
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    $sql = "INSERT INTO users (username, password, full_name, role, email, phone_number) VALUES ('$username', '$password', '$full_name', '$role', '$email', '$phone_number')";

    if ($conn->query($sql) === TRUE) {
        echo "Регистрация успешна. Теперь вы можете авторизоваться.";
    } else {
        echo "Ошибка при регистрации: " . $conn->error;
    }
}

$conn->close();
?>