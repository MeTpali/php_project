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

        <!-- Форма для регистрации нового пользователя -->
        <form class="card" action="src/actions/register.php" method="post">
            <h2>Регистрация</h2>

            <!-- Поле для ввода имени -->
            <label for="name">
                Имя
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Иванов Иван"
                    value="<?php echo old('name') ?>"
                    <?php echo validationErrorAttr('name'); ?>
                    > 
                <?php if(hasValidationError('name')): ?>
                    <small><?php echo validationErrorMessage('name'); ?></small>
                <?php endif; ?>
            </label>

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
                
                <?php if(hasValidationError('email')): ?>
                    <small><?php echo validationErrorMessage('email'); ?></small>
                <?php endif; ?>
            </label>

            <!-- Поле для ввода пароля -->
            <label for="password">
                Пароль
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="******"
                    <?php echo validationErrorAttr('password'); ?> <!-- Аттрибут для ошибок валидации -->
                <?php if(hasValidationError('password')): ?> <!-- Если есть ошибка валидации для пароля, показываем её -->
                    <small><?php echo validationErrorMessage('password'); ?></small>
                <?php endif; ?>
            </label>

            <!-- Кнопка для отправки формы -->
            <button
                type="submit"
                id="submit"
            >Продолжить</button>
        </form>

        <!-- Ссылка на страницу входа, если у пользователя уже есть аккаунт -->
        <p>У меня уже есть <a href="/">аккаунт</a></p>

        <?php include_once __DIR__ . '/components/scripts.php' ?> <!-- Подключаем скрипты -->
    </body>
</html>
