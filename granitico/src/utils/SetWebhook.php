<?php

require __DIR__ . '/../config/bot_config.php';


$url = 'https://api.telegram.org/bot' . BOT_TOKEN . '/setWebhook?url=' . WEB_HOOK_URL;

// Отправляем запрос на установку вебхука
$response = file_get_contents($url);

if ($response) {
  echo $response . PHP_EOL;
    echo "Webhook успешно установлен!";
} else {
    echo "Произошла ошибка при установке вебхука.";
}