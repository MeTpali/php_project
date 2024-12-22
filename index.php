<?php
// Подключаем вспомогательные функции
require_once __DIR__ . '/src/helpers.php';

// Проверяем, не авторизован ли пользователь (если авторизован, перенаправляем)
checkGuest();
?>

<!DOCTYPE html>
<html lang="ru" data-theme="light">
    <?php include_once __DIR__ . '/components/head.php'?> <!-- Подключаем метаинформацию и стили -->
    <body>

        <!-- Форма для авторизации пользователя -->
        <form class="card" action="src/actions/login.php" method="post">
            <h2>Вход</h2>

            <!-- Если есть сообщение об ошибке, отображаем его -->
            <?php if(hasMessage('error')): ?>
                <div class="notice error"><?php echo getMessage('error') ?></div>
            <?php endif; ?>

            <!-- Поле для ввода e-mail -->
            <label for="email">
                E-mail
                <input
                    type="text"
                    id="email"
                    name="email"
                    placeholder="youremail@example.com"
                    value="<?php echo old('email') ?>"
                    <?php echo validationErrorAttr('email'); ?>
                >  
                <?php if(hasValidationError('email')): ?>
                    <small><?php echo validationErrorMessage('email'); ?></small>
                <?php endif; ?>
            </label>

            <label for="password">
                Пароль
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="******"
                >
            </label>

            <!-- Кнопка для отправки формы -->
            <button
                type="submit"
                id="submit"
            >Продолжить</button>
        </form>

        <!-- Ссылка на страницу регистрации, если у пользователя нет аккаунта -->
        <p>У меня еще нет <a href="/register.php">аккаунта</a></p>

        <?php include_once __DIR__ . '/components/scripts.php' ?> <!-- Подключаем скрипты -->
    </body>
</html>
