<?php
class SessionsOBJ extends DBObject {
    public $objectName = 'Sessions';
    public $table = 'sessions_users';
    public $tempResult = false;
    public $primarykey = "id";
    public function __construct() {
	parent::__construct('sessions_users');
        $this->noShowFields = array();
        $this->TagLogName = "Sessions";
    } 
    
    public function set($params,$returnError = false,$blockInsert = false){
        $qSelect = "SELECT * FROM sessions_users WHERE id_user = '".$params['id_user']."'";
        $rSelect = mysqli_query(DataBase::$mysqli, $qSelect);
        if(mysqli_num_rows($rSelect) > 0){
            $params['id'] = DataBase::getResult($rSelect, 'id');
        }
        $params['session_start'] = date('Y-m-d H:i:s');
        $params['session_end'] = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'). " + 6 hours"));
        $params['token'] = generateToken();
        while(mysqli_num_rows(mysqli_query(DataBase::$mysqli, "SELECT id FROM sessions_users WHERE token = '".$params['token']."'")) > 0){
            $params['token'] = generateToken();
        }
        if($blockInsert == false){$message = $this->popolateArray($params,$this->objectName);}
        return $params;
    }

    public function select($whereFields = array(),$addQuery = ""){
        $q = "SELECT * FROM ".$this->table;
        if(is_array($whereFields) && count($whereFields)>0){
            $filters = "";
            foreach($whereFields as $whereField){
                
                $name = explode(" ", $whereField)[0];
                if($this->isColumn($name) != false){
                    $filters .= " AND ".$whereField;
                }
            }
            if(strlen($filters) > 0){
                $q .= " WHERE ". removeFirstChars($filters,4);
            }
        }else{
            if(!is_array($whereFields)){
                $q .= " WHERE ".$this->primarykey." = '".addslashes($whereFields)."'";
            }
            
        }
        
        $this->tempQuery = $q." ".$addQuery;
        return $this;
    }

    public static function setCookie(string $name, string $value, string $date,string $path = '/', string $domain = '', $secure = false, $httponly = false){
        $result = setcookie($name,$value,$date,$path,$domain,$secure,$httponly);
        if($result || (isset($_COOKIE[$name]) && strlen($_COOKIE[$name]) > 0)){
            return true;
        }
        return false;
    }
    
    public static function unsetCookie(string $name){
        unset($_COOKIE[$name]); 
        setcookie($name,'',time()-1,'/');
        if(!isset($_COOKIE[$name])){
            return true;
        }
        return false;
    }
}