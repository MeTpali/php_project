<?php

// Подключаем файл с вспомогательными функциями
require_once __DIR__ . '/../helpers.php';

// Извлекаем данные из POST-запроса и присваиваем их переменным
$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

// Проверяем введённое имя на пустоту
if (empty($name)) {
    // Добавляем сообщение об ошибке валидации для имени
    setValidationError('name', 'Неверное имя');
}

// Проверяем, соответствует ли email правильному формату
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    setValidationError('email', 'Указана неправильная почта');
}

// Генерируем соль для шифрования
$salt = uniqid(); // Создаём уникальную строку для соли
$encryptedEmail = crypt($email, $salt); // Шифруем email с использованием соли

// Проверяем, существует ли уже пользователь с таким email
if (isEmailExists($encryptedEmail)) {
    // Устанавливаем сообщение об ошибке, если почта занята
    setValidationError('email', 'Почта уже используется');
    
    // Сохраняем предыдущие значения полей для повторного отображения
    setOldValue('name', $name);
    setOldValue('email', $email);
    
    // Перенаправляем обратно на форму регистрации
    redirect('/register.php');
}

// Проверяем, чтобы пароль не был пустым
if (empty($password)) {
    setValidationError('password', 'Пароль пустой');
}

// Если в сессии есть ошибки валидации, перенаправляем обратно на форму
if (!empty($_SESSION['validation'])) {
    // Сохраняем старые значения для полей формы
    setOldValue('name', $name);
    setOldValue('email', $email);
    redirect('/register.php');
}

// Получаем объект PDO для работы с базой данных
$pdo = getPDO();

// Формируем SQL-запрос для добавления нового пользователя
$query = "INSERT INTO users_a (name, email, password) VALUES (:name, :email, :password)";

// Подготавливаем параметры для выполнения запроса
$params = [
    'name' => $name,
    'email' => $encryptedEmail,
    'password' => crypt($password, $salt) // Шифруем пароль с использованием той же соли
];

// Готовим и выполняем SQL-запрос
$stmt = $pdo->prepare($query);

try {
    $stmt->execute($params); // Выполняем запрос с параметрами
} catch (\Exception $e) {
    // В случае ошибки выводим её сообщение и завершаем выполнение скрипта
    die($e->getMessage());
}

// После успешной регистрации перенаправляем пользователя на главную страницу
redirect('/');
