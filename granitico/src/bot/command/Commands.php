<?php

namespace bot\command;

use model\AuthModel;
use model\DBConnection;
use PDO;

require __DIR__ . '/../../config/connection-database.php';
require __DIR__ . '/../../config/autoload-functions.php';

class Commands
{
  public function messageStart($chatID, $userName)
  {
    $message = "Привет, $userName! Я телеграм-бот Гранитико. Буду присылать вам заявки с сайта гранит.сайт. Пожалуйста, авторизуйтесь через кнопку Войти и введите пароль администратора, чтобы получать заявки.";

    $keyboard = [
      'keyboard' => [        
        [['text' => 'Войти']]
      ],
      'resize_keyboard' => true,
      'one_time_keyboard' => true
    ];

    $params = [
      'chat_id' => $chatID,
      'text' => $message,
      'reply_markup' => json_encode($keyboard)
    ];

    return $params;
  }  

  public function login($chatID, $userLogin, $userName)
  {
    $db = new DBConnection();
    $pdoConnection = $db->getConnection();
    $dbBotAdminAccount = new AuthModel($pdoConnection);
    $adminData = $dbBotAdminAccount->getIsAdmin($userLogin);  
    
    if (isset($adminData['admin_name'])) {      
      $dbBotAdminAccount->authAdmin($userLogin, $chatID);
      $message = "Приветствую $userName, пожалуйста введите пароль и нажмите отправить";
    } else {
      $message = 'В доступе отказано, Вас нет в списке администраторов /start';
    }    

    $params = [
      'chat_id' => $chatID,
      'text' => $message,
    ];
    
    return $params;
  }

  public function checkAdminPassword($chatID, $userLogin, $message)
  {
    $db = new DBConnection();
    $pdoConnection = $db->getConnection();
    $dbBotAdminAccount = new AuthModel($pdoConnection);
    $adminData = $dbBotAdminAccount->getPassVerif($userLogin);

    if(password_verify($message, $adminData['password_hash']))
    {
      $dbBotAdminAccount->passVerifSuccess($userLogin);
      $message = 'Вы успешно авторизовались. Теперь в этот чат будут приходить заявки с сайта гранит.сайт';
    } else {
      $message = 'Неверно указан пароль';
    }

    $params = [
      'chat_id' => $chatID,
      'text' => $message,
    ];

    return $params;
  }

  public function messageUnknown($chatID)
  {
    $message = "Я не знаю, что с этим делать. Вы можете авторизоваться, нажав на кнопку Войти";

    $keyboard = [
      'keyboard' => [
        [['text' => 'Войти']]
      ],
      'resize_keyboard' => true,
      'one_time_keyboard' => true
    ];

    $params = [
      'chat_id' => $chatID,
      'text' => $message,
      'reply_markup' => json_encode($keyboard)
    ];

    return $params;
  }

  public function messageForAuth($chatID)
  {
    $message = "В этом чате я показываю только заявки от пользователей сайта гранит.сайт. Команды не принимаются.";
   
    $params = [
      'chat_id' => $chatID,
      'text' => $message,      
    ];

    return $params;
  }

  public function messageForAuthButton($chatID)
  {
    $message = "Вы уже авторизованы, я сообщу когда придёт заяка от пользователей сайта гранит.сайт";

    $params = [
      'chat_id' => $chatID,
      'text' => $message,
    ];

    return $params;
  }

  public function messageExit($chatID, $userLogin)
  {
    $db = new DBConnection();
    $pdoConnection = $db->getConnection();
    $dbBotAdminAccount = new AuthModel($pdoConnection);
    $dbBotAdminAccount->logout($userLogin);

    $message = "Вы вышли из чата заявок, заявки с сайта гранит.сайт больше не приходят. Нажмите кнопку Войти, если хотите снова получать заявки";

    $params = [
      'chat_id' => $chatID,
      'text' => $message,
    ];

    return $params;
  }
}