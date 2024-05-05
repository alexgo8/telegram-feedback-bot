<?php

use bot\command\Commands;
use model\AuthModel;
use model\DBConnection;

require __DIR__ . '/../config/autoload-functions.php';
require __DIR__ . '/../config/bot_config.php';
require __DIR__ . '/../config/connection-database.php';

$userData = json_decode(file_get_contents("php://input"), true);

$message = $userData["message"]["text"];
$chatID = $userData["message"]["chat"]["id"];
$userName = $userData["message"]["chat"]["first_name"];
$userLogin = $userData["message"]["chat"]["username"];

$db = new DBConnection();
$pdoConnection = $db->getConnection();
$dbBotAdminAccount = new AuthModel($pdoConnection);
$adminData = $dbBotAdminAccount->getChatIdAndIsAuth($userLogin);


if ($adminData['chat_id'] == $chatID) {
  $isCurrentChatActive = true;
} else {
  $isCurrentChatActive = false;
}

if ($adminData['is_admin_authenticated'] == true) {
  $isLoggedIn = true;
} else {
  $isLoggedIn = false;
}

$exitWords = array('Выход', 'выход', 'Выйти', 'выйти', 'exit', '/exit', 'Exit');

$commands = new Commands();

if ($message == "/start" and $isCurrentChatActive == false) {
  $botResponseParams = $commands->messageStart($chatID, $userName);
} elseif ($message == "Войти" and $isCurrentChatActive == false) {
  $botResponseParams = $commands->login($chatID, $userLogin, $userName);
} elseif ($isCurrentChatActive == true and $isLoggedIn == false) {
  $botResponseParams = $commands->checkAdminPassword($chatID, $userLogin, $message);
} elseif ($isCurrentChatActive == false and $isLoggedIn == false) {
  $botResponseParams = $commands->messageUnknown($chatID);
} elseif ($message == "Войти" and $isCurrentChatActive == true and $isLoggedIn == true) {
  $botResponseParams = $commands->messageForAuthButton($chatID);
} elseif (in_array($message, $exitWords) and $isCurrentChatActive == true and $isLoggedIn == true) {
  $botResponseParams = $commands->messageExit($chatID, $userLogin);
} elseif ($isCurrentChatActive == true and $isLoggedIn == true) {
  $botResponseParams = $commands->messageForAuth($chatID);
}

$options = array(
  'http' => array(
    'method' => 'POST',
    'header' => 'Content-Type: application/x-www-form-urlencoded',
    'content' => http_build_query($botResponseParams)
    )
  );
  
  $context = stream_context_create($options);

  $responseUrl = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage";

  $result = file_get_contents($responseUrl, false, $context);