<?php

// Запуск сессии для работы с глобальными переменными
session_start();

// Подключение файла с конфигурацией, в котором хранятся параметры подключения к базе данных
require_once __DIR__ . '/config.php';

// Функция для перенаправления на указанный путь
function redirect(string $path)
{
    header("Location: $path");  // Отправка заголовка для перенаправления
    die();  // Прекращение дальнейшего выполнения скрипта
}

// Функция для установки ошибки валидации для конкретного поля формы
function setValidationError(string $fieldName, string $message): void
{
    $_SESSION['validation'][$fieldName] = $message;  // Сохранение ошибки в сессии
}

// Функция для проверки наличия ошибки валидации для конкретного поля
function hasValidationError(string $fieldName): bool
{
    return isset($_SESSION['validation'][$fieldName]);  // Проверка наличия ошибки
}

// Функция для добавления атрибута aria-invalid в HTML, если для поля есть ошибка
function validationErrorAttr(string $fieldName): string
{
    return isset($_SESSION['validation'][$fieldName]) ? 'aria-invalid="true"' : '';  // Добавление атрибута если ошибка есть
}

// Функция для получения сообщения об ошибке валидации для конкретного поля
function validationErrorMessage(string $fieldName): string
{
    $message = $_SESSION['validation'][$fieldName] ?? '';  // Получаем сообщение об ошибке
    unset($_SESSION['validation'][$fieldName]);  // Удаляем сообщение после того, как оно было получено
    return $message;
}

// Функция для сохранения старого значения (например, для автозаполнения формы)
function setOldValue(string $key, mixed $value): void
{
    $_SESSION['old'][$key] = $value;  // Сохранение старого значения в сессии
}

// Функция для получения старого значения (например, для автозаполнения формы)
function old(string $key)
{
    $value = $_SESSION['old'][$key] ?? '';  // Получаем старое значение
    unset($_SESSION['old'][$key]);  // Удаляем старое значение после его получения
    return $value;
}

// Функция для сохранения общего сообщения (например, об успешной операции)
function setMessage(string $key, string $message): void
{
    $_SESSION['message'][$key] = $message;  // Сохранение сообщения в сессии
}

// Функция для проверки наличия общего сообщения в сессии
function hasMessage(string $key): bool
{
    return isset($_SESSION['message'][$key]);  // Проверка наличия сообщения
}

// Функция для получения общего сообщения из сессии
function getMessage(string $key): string
{
    $message = $_SESSION['message'][$key] ?? '';  // Получаем сообщение
    unset($_SESSION['message'][$key]);  // Удаляем сообщение после получения
    return $message;
}

// Функция для получения объекта PDO для работы с базой данных
function getPDO(): PDO
{
    try {
        static $pdo = null;  // Статическая переменная для кеширования объекта PDO
        
        if ($pdo === null) {
            // Создание строки DSN для подключения к базе данных
            $dsn = sprintf(
                "pgsql:host=%s;port=%s;dbname=%s",  // Формирование строки для PostgreSQL
                DB_HOST,
                DB_PORT,
                DB_NAME
            );
            
            // Настройки для PDO
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Включаем исключения для ошибок
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // По умолчанию возвращаем ассоциативный массив
                PDO::ATTR_EMULATE_PREPARES => false  // Отключаем эмуляцию подготовленных запросов
            ];
            
            // Создание объекта PDO
            $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
        }
        
        return $pdo;  // Возвращаем объект PDO для работы с базой данных
        
    } catch (PDOException $e) {
        // В случае ошибки подключения логируем информацию и завершаем выполнение
        error_log("Database connection failed: " . $e->getMessage());
        die("Database connection failed. Please try again later.");
    }
}

// Функция для поиска пользователя по зашифрованному email
function findUser(string $email): array|bool
{
    $pdo = getPDO();  // Получаем объект PDO для работы с базой
    
    // Подготовка SQL-запроса для поиска пользователя по email
    $stmt = $pdo->prepare("SELECT * FROM users_a WHERE email = :email");
    $stmt->execute(['email' => $email]);  // Выполнение запроса
    
    if (!$stmt->rowCount()) {
        return false;  // Если пользователь не найден, возвращаем false
    }
    
    $user = $stmt->fetch();  // Получаем данные пользователя
    return $user;  // Возвращаем информацию о пользователе
}

// Функция для проверки существования email в базе данных
function isEmailExists($email)
{
    $pdo = getPDO();  // Получаем объект PDO для работы с базой
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users_a WHERE email = ?");  // Запрос для проверки существования email
    $stmt->execute([$email]);  // Выполнение запроса
    return (bool)$stmt->fetchColumn();  // Возвращаем true, если email существует
}

// Функция для получения текущего пользователя по ID из сессии
function currentUser(): array|false
{
    if (!isset($_SESSION['user'])) {
        return false;  // Если нет информации о пользователе в сессии, возвращаем false
    }

    $userId = $_SESSION['user']['id'] ?? null;  // Получаем ID пользователя из сессии
    $pdo = getPDO();  // Получаем объект PDO для работы с базой
    
    // Подготовка SQL-запроса для получения пользователя по ID
    $stmt = $pdo->prepare("SELECT * FROM users_a WHERE id = :id");
    $stmt->execute(['id' => $userId]);  // Выполнение запроса
    
    if (!$stmt->rowCount()) {
        return false;  // Если пользователь не найден, возвращаем false
    }
    
    $user = $stmt->fetch();  // Получаем данные пользователя
    return $user;  // Возвращаем информацию о пользователе
}

// Функция для выхода пользователя из системы
function logout(): void
{
    unset($_SESSION['user']['id']);  // Удаляем данные о пользователе из сессии
    redirect('/');  // Перенаправляем на главную страницу
}

// Функция для проверки авторизации пользователя
function checkAuth(): void
{
    if (!isset($_SESSION['user']['id'])) {
        redirect('/');  // Если пользователь не авторизован, перенаправляем на главную
    }
}

// Функция для проверки, является ли пользователь гостем (не авторизован)
function checkGuest(): void
{
    if (isset($_SESSION['user']['id'])) {
        redirect('/home.php');  // Если пользователь уже авторизован, перенаправляем на страницу профиля
    }
}
