<?php

// Подключаем вспомогательные функции
require_once __DIR__ . '/../helpers.php';

// Получаем значения email и password из POST-запроса, если они есть, иначе присваиваем null
$email = $_POST['email'] ?? null;
$password = $_POST['password'] ?? null;

// Проверка email: он не должен быть пустым и должен быть в правильном формате
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Сохраняем введённое значение email для повторного отображения
    setOldValue('email', $email);
    
    // Устанавливаем сообщение об ошибке валидации для email
    setValidationError('email', 'Неверный формат электронной почты');
    
    // Добавляем общее сообщение об ошибке
    setMessage('error', 'Ошибка валидации');
    
    // Перенаправляем обратно на главную страницу
    redirect('/');
}

// Генерация соли для шифрования
$salt = uniqid(); // Генерируем уникальную строку

// Шифруем email с использованием соли
$cryptedEmail = crypt($email, $salt);

// Ищем пользователя в базе данных по зашифрованному email
$user = findUser($cryptedEmail);

// Если пользователь не найден, устанавливаем сообщение об ошибке и перенаправляем
if (!$user) {
    setMessage('error', "Пользователь $email не найден");
    redirect('/');
}

// Шифруем пароль с использованием той же соли
$cryptedPassword = crypt($password, $salt);

// Сравниваем зашифрованный пароль с сохранённым в базе данных
if ($cryptedPassword !== $user['password']) {
    // Если пароли не совпадают, устанавливаем сообщение об ошибке и перенаправляем
    setMessage('error', 'Неверный пароль');
    redirect('/');
}

// Если проверка успешна, сохраняем данные пользователя в сессии
$_SESSION['user']['id'] = $user['id'];
$_SESSION['user']['name'] = $user['name'];

// Перенаправляем на домашнюю страницу
redirect('/home.php');
