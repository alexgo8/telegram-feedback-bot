<?php

namespace model;

use PDO;
use PDOException;

require __DIR__ . '/../config/connection-database.php';


class DBConnection
{
  private $db_connection = DB_CONNECTION;
  private $host = DB_HOST;
  private $db_name = DB_DATABASE;
  private $username = DB_USERNAME;
  private $password = DB_PASSWORD;
  private $connect;
  
  public function getConnection()
  {
    try {
      $this->connect = new PDO(
        $this->db_connection . ':host=' . $this->host . ';dbname=' . $this->db_name,
        $this->username,
        $this->password
      );
      $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo 'Connection Error: ' . $e->getMessage();
    }

    return $this->connect;
  }
}