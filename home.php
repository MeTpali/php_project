<?php
// Подключаем вспомогательные функции
require_once __DIR__ . '/src/helpers.php';

// Проверяем, авторизован ли пользователь
checkAuth();

// Получаем информацию о текущем пользователе
$user = currentUser();
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
    <?php include_once __DIR__ . '/components/head.php'?> <!-- Подключаем метаинформацию и стили -->
    <body>

        <div class="card home">
            <!-- Приветственное сообщение с именем пользователя -->
            <h1>Привет, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</h1>
            
            <!-- Форма для выхода из аккаунта -->
            <form action="src/actions/logout.php" method="post">
                <!-- Кнопка для отправки POST-запроса на выход -->
                <button role="button">Выйти из аккаунта</button>
            </form>
        </div>

        <?php include_once __DIR__ . '/components/scripts.php' ?> <!-- Подключаем скрипты -->
    </body>
</html>
