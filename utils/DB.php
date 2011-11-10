<?php

class Database {
    private $conn = null;

    private $dbhost = 'localhost';
    private $dbuser = 'root';
    private $dbpass = '';
    private $dbname = "workhours"; 
   
    public function get_connection() {
        return $this->conn;
    }
    
    public function connect () {       
        if(!isset($this->conn)) {
            $this->conn = @mysql_connect($this->dbhost, $this->dbuser, $this->dbpass) or die ('Error connecting to mysql');
            mysql_select_db($this->dbname);
        }
    }
    
    function __destruct() {
        if(isset($this->conn)) {
            @mysql_close($this->conn);
        }
    }
}
    
?>