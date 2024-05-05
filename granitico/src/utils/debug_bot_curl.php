<?php

$jsonData = require 'example_response_from_telegram_api.json';
require __DIR__ . '/../config/bot_config.php';

// Инициализация cURL сессии
$ch = curl_init(WEB_HOOK_URL);

// Установка опций cURL
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); // Отправляем POST запрос
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData); // Устанавливаем данные для отправки
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Ожидаем получить ответ
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //Игнорировать проверку сертификата 

// Устанавливаем заголовок Content-Type для JSON данных
curl_setopt(
  $ch,
  CURLOPT_HTTPHEADER,
  array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData)
  )
);

// Выполнение запроса
$response = curl_exec($ch);

// Проверка на ошибки
if (curl_errno($ch)) {
  echo 'Ошибка curl: ' . curl_error($ch);
}

// Закрытие cURL сессии
curl_close($ch);

// Вывод ответа
echo $response;