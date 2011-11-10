<?php
class App {
    var $db = null;
    var $user = null;
    var $action = null;
    var $default_action = 'pwa';

    function __construct() {
        $this->db = new Database();
        $this->db->connect();
    }
    
    public function run() {
		$this->action = isset($_REQUEST['a'])? $_REQUEST['a'] : $this->default_action;

        if($this->action == "logout") 
        {
            unset($_SESSION['user']);
            unset($this->user);
            $t = new Template();    
            echo $t->fetch("./template/logout.t");
            return;
        }
        
        if(!$this->authenticate($_REQUEST, $_SESSION)) {
            $t = new Template(); 
            if(isset($_REQUEST["login"])) {
                $t->set('error', "1");
            }
            echo $t->fetch("./template/login.t");
            return;
        } else {
            $t = new Template(); 

            $t->set('action',$this->action);
            echo $t->fetch("./template/layout.t");
            return;
        }

    }
    
    function authenticate($cgi, &$sess) {

        if(isset($sess['user'])) {
            $this->user = unserialize($sess['user']);
            return true;
        } else {
            if(!isset($cgi['username']) || !isset($cgi['password']))
                return false;            
            $query = "SELECT * 
                                            FROM employee 
                                            WHERE `Initials`=\"".$cgi['username']."\" AND 
                                            `Password`=MD5(\"".$cgi['password']."\")";
            $result = mysql_query($query);
            if ($row = mysql_fetch_array($result)) {
                $this->user['initials'] = $cgi->username;
                $this->user['id'] = $row["ID"];
                $sess['user'] = serialize($this->user);
                return true;
            } else {
                return false;            
            }
        }
    }
}
?>