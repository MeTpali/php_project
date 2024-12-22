<?php

// Подключаем вспомогательные функции
require_once __DIR__ . '/../helpers.php';

// Проверяем, что запрос был отправлен методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Вызываем функцию logout для завершения сессии пользователя
    logout();
}

// Перенаправляем на страницу home.php
redirect('/home.php');
