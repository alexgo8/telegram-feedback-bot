<?php

require __DIR__ . '/../config/bot_config.php';


// URL для метода getMe в API Telegram Bot
$url = "https://api.telegram.org/bot" . BOT_TOKEN . "/getMe";
$response = file_get_contents($url);

// Проверяем, удалось ли получить ответ
if ($response !== false) {
// Декодируем ответ из JSON в массив
$responseData = json_decode($response, true);

if ($responseData['ok']) {
echo "Связь с Telegram ботом установлена корректно.\n";
echo "Имя бота: " . $responseData['result']['first_name'] . "\n";
echo "Идентификатор бота: " . $responseData['result']['id'] . "\n";
} else {
echo "Ошибка в ответе API: " . $responseData['description'] . "\n";
}
} else {
echo "Не удалось получить ответ от API.\n";
}