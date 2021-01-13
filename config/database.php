<?php
class Database{
 
    // specify your own database credentials
	//LOCALHOST BD
    private $host = "localhost";      private $db_name = "centra22_general";    private $username = "centra22_admin";    private $password = "RealMadrid84*";

	//AMAZON BD
	// private $host = "dblaminas.ccdcz35oayg6.us-east-2.rds.amazonaws.com";	 private $db_name = "laminas";    private $username = "admin";    private $password = "T3cn1m3x.";

    //SU SERVER
   // private $host = "localhost";    private $db_name = "centra22_general";    private $username = "centra22_admin";    private $password = "RealMadrid84*";
  
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>