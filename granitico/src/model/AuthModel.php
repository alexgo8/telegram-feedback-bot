<?php

namespace model;

use PDO;


class AuthModel
{
  private $connect;

  public function __construct($PDOConnection)
  {
    $this->connect = $PDOConnection;
  }
  
  public function getAdminsAuthInfo()
  {
    $query = 'SELECT chat_id FROM bot_admin_accounts WHERE is_admin_authenticated = 1 AND chat_id IS NOT NULL';    $statement = $this->connect->prepare($query);
    $statement->execute();
    $adminsData = $statement->fetchAll($this->connect::FETCH_ASSOC);
    return $adminsData;
  }
  
  public function getChatIdAndIsAuth($userLogin)
  {
    $query = "SELECT chat_id, is_admin_authenticated FROM bot_admin_accounts WHERE admin_name = :userLogin";
    $statement = $this->connect->prepare($query);
    $statement->bindParam(':userLogin', $userLogin, PDO::PARAM_STR);
    $statement->execute();
    $adminData = $statement->fetch(PDO::FETCH_ASSOC);
    return $adminData;
  }

  public function getIsAdmin($userLogin)
  {
    $query = "SELECT admin_name FROM bot_admin_accounts WHERE admin_name = :userLogin";
    $statement = $this->connect->prepare($query);
    $statement->bindParam(':userLogin', $userLogin, PDO::PARAM_STR);
    $statement->execute();
    $adminData = $statement->fetch(PDO::FETCH_ASSOC);
    return $adminData;
  }

  public function authAdmin($userLogin, $chatID)
  {
    $query = "UPDATE bot_admin_accounts SET chat_id = :chatID WHERE admin_name = :userLogin";
    $statement = $this->connect->prepare($query);
    $statement->bindParam(':userLogin', $userLogin, PDO::PARAM_STR);
    $statement->bindParam(':chatID', $chatID, PDO::PARAM_STR);
    $statement->execute();
  }

  public function getPassVerif($userLogin)
  {
    $query = "SELECT password_hash FROM bot_admin_accounts WHERE admin_name = :userLogin";
    $statement = $this->connect->prepare($query);
    $statement->bindParam(':userLogin', $userLogin, PDO::PARAM_STR);
    $statement->execute();
    $adminData = $statement->fetch(PDO::FETCH_ASSOC);
    return $adminData;
  }

  public function passVerifSuccess($userLogin)
  {
    $query = "UPDATE bot_admin_accounts SET is_admin_authenticated = 1 WHERE admin_name = :userLogin";
    $statement = $this->connect->prepare($query);
    $statement->bindParam(':userLogin', $userLogin, PDO::PARAM_STR);
    $statement->execute();
  }

  public function logout($userLogin)
  {
    $query = "UPDATE bot_admin_accounts SET is_admin_authenticated = 0, chat_id = null WHERE admin_name = :userLogin";
    $statement = $this->connect->prepare($query);
    $statement->bindParam(':userLogin', $userLogin, PDO::PARAM_STR);
    $statement->execute();
  }
}