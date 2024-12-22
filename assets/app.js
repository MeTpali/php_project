// Получаем элемент с идентификатором 'terms' (чекбокс согласия с условиями)
const terms = document.getElementById('terms');

// Получаем элемент с идентификатором 'submit' (кнопка отправки формы)
const submit = document.getElementById('submit');

// Добавляем обработчик события для изменения состояния чекбокса
terms.addEventListener('change', (e) => {
    // Устанавливаем состояние кнопки 'submit' в зависимости от того, отмечен ли чекбокс
    // Если чекбокс отмечен, кнопка активна; иначе - отключена
    submit.disabled = !e.currentTarget.checked;
});
