<?php

use model\AuthModel;
use model\DBConnection;

require __DIR__ . '/../config/bot_config.php';
require __DIR__ . '/../config/autoload-functions.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = htmlspecialchars($_POST['name'] ?? '');
  $phone = htmlspecialchars($_POST['phone'] ?? '');
  $comment = htmlspecialchars($_POST['comment'] ?? '');
}

$message = "$comment\n Пользователь $name телефон $phone оставил заявку на сайте гранит.сайт. Пожалуйста, перезвоните ему в ближайшее время";

$db = new DBConnection();
$pdoConnection = $db->getConnection();
$dbBotAdminAccounts = new AuthModel($pdoConnection);
$botAdminsData = $dbBotAdminAccounts->getAdminsAuthInfo();

$responseUrl = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage";


foreach ($botAdminsData as $adminData) {

  $botResponseParams = array(
    'chat_id' => $adminData['chat_id'],
    'text' => $message,
  );

  $options = array(
    'http' => array(
      'method' => 'POST',
      'header' => 'Content-Type: application/x-www-form-urlencoded',
      'content' => http_build_query($botResponseParams)
    )
  );

  $context = stream_context_create($options);
  $result = file_get_contents($responseUrl, false, $context);
}