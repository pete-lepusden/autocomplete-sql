<?php
class Dao
{
  private $server = "mysql:host=localhost;dbname=core";
  private $user = "root";
  private $pass = "As1R@&&!tdb";
  private $options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  );

  protected $con;

  public function openConnection()
  { try
    { $this->con = new PDO($this->server, $this->user, $this->pass, $this->options);
      return $this->con;
    }
    catch (PDOException $e) { echo "There is a problem in new pdo connection: " . $e->getMessage(); }
  }

  public function closeConnection() { $this->con = null; }
}
?>
