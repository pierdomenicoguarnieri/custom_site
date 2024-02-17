<?php
class DBObject extends stdClass
{
    public $table;
    protected $fields;
    public $noShowFields = array();
    public $tempQuery = "";
    public $tempResult = false;
    public $hasResult = false;
    public $TagLogName = "DBObject";
    public $primarykey = "id";
    public function __construct($table,$jsonFields = null){
        $this->table = $table;
        if(isset($jsonFields)){
            $this->fields = $jsonFields;
            $this->primarykey = ($this->isColumn("id")) ? "id" : "code";
        }else{
            $this->fields = array();
            $q = "SELECT * FROM ".$this->table." LIMIT 1";
            $r = mysqli_query(DataBase::$mysqli,$q);
            if($r != false){
                while ($fieldinfo = mysqli_fetch_field($r))
                {
                    $f = new stdClass();
                    $f->name = $fieldinfo->name;
                    $f->type = $fieldinfo->type;
                    array_push($this->fields, $f);
                }
                $this->primarykey = ($this->isColumn("id")) ? "id" : "code";
            }else{
                //Log::Write(mysqli_error(DataBase::$mysqli),$this->TagLogName);
            }
        }
        
    }
    
    public function getProperty($obj,$name,$default = ""){
        $txt = $obj->{$name};
        $txt = trim($txt);
        if(strlen($txt)>0){
            return $txt;
        }else{
            return $default;
        }
    }
    
    public function getPropertyValue($TheObj,$obj,$name){
        $isset = (strlen($TheObj->getProperty($obj, $name))>0) ? $TheObj->getProperty($obj, $name) : ""; 
        if(strlen($isset) == 0 || $isset == "nulldate"){
            $isset = (strlen(getPost($name)) > 0) ? getPost($name) : getGet($name);
        }
        if($isset == "id")return "";
        return $isset;
    }
    
    public function isColumn($name){
        $exists = false;
        foreach($this->fields as $field){
            if($field->name == $name){
                $exists = $field;
            }
        }
        return $exists;
    }
    
    public function formatColumn($name,$value,$isOutput = false){
        $field = $this->isColumn($name);
        $formatted = "";
        if($field != false){
            if(isset($value)){
                switch ($field->type) {
                    case MYSQLI_TYPE_INT24:
                        $formatted = intval($value);
                        break;
                    case MYSQLI_TYPE_DOUBLE:
                        $formatted = str_replace(",", ".",$value);
                        break;
                    case MYSQLI_TYPE_TIMESTAMP:
                        $formatted = ($isOutput && strlen($value) == 19) ? $this->data_eng_ita(explode(" ", $value)[0])." ". explode(":", explode(" ", $value)[1])[0].":".explode(":", explode(" ", $value)[1])[1] : $value;
                        break;
                    case MYSQLI_TYPE_DATE:
                        if(strlen($value) == 10){
                            $formatted = ($isOutput) ? $this->data_eng_ita($value) : $this->data_ita_eng($value);
                        }else{
                            $formatted = "nulldate";
                        }
                        break;
                    default:
                        if(strlen($value)==0){
                            $formatted = " ";
                        }else{
                            $formatted = ($isOutput) ? str_replace("''","'",$value) : str_replace("'","''",$value);
                        }
                        
                } 
            }
        }
        return trim($formatted);
    }
    
    public function select($whereFields = array(),$addQuery = ""){
        $q = "SELECT * FROM ".$this->table;
        if(is_array($whereFields) && count($whereFields)>0){
            $filters = "";
            // var_dump($whereFields);die;
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
    
    public function getSingle($val){
        $q = "SELECT * FROM ".$this->table." WHERE ".$this->primarykey." = '".$val."'";
        $this->setQuery($q);
        $this->load();
        return $this;
    }

    public function setQuery($q){
        $this->tempQuery = $q;
        return $this;
    }
    
    public function load()
    {
        $this->tempResult = DataBase::getQuery($this->tempQuery);
        if($this->tempResult == false){
            $this->hasResult = false;
            //Log::Write(mysqli_error(DataBase::$mysqli),$this->TagLogName);
        }else{
            if(mysqli_num_rows($this->tempResult)>0){
                $this->hasResult = true;
            }else{
                $this->hasResult = false;
            }
        }
    }
    
    public function count(){
        if($this->hasResult != false){
            return intval(mysqli_num_rows($this->tempResult));
        }else{
            return 0;
        }
        
    }

    public function get($id = 0)
    {
        $obj = new stdModel;
        foreach($this->fields as $field){
            if(!in_array($field->name, $this->noShowFields)){
                $value = ($this->hasResult != false) ? DataBase::getResult($this->tempResult,$field->name,$id) : "";
                $obj->{$field->name} = $this->formatColumn($field->name,$value,true);
            }            
        }
        return $obj;
    }
    
    public function getAll()
    {
        $obj = array();
        for($i=0;$i<mysqli_num_rows($this->tempResult);$i++){
            array_push($obj, $this->get($i));
        }
        return $obj;
    }
    public function deleteOBJ($id,$objname = null){
        $jsonEdit = $this->getSingle($id);
        $jsonEdit = $jsonEdit->get();
        $q = "DELETE FROM ".$this->table." WHERE id = ".intval($id);
        $r = mysqli_query(DataBase::$mysqli,$q);
        if($r != false){
            if(intval($GLOBALS['currentLoggedUser']->id)>0 && $objname != null){
                $cod_type_wizard = 8;
                $_GET['gobj'] = $objname;
                $_GET['gcod_obj'] = $id;
                $registration = new WizardRegistrationOBJ();
                $registration->set(array(
                    'cod_user' => $GLOBALS['currentLoggedUser']->id,
                    'info' => base64_encode(json_encode($jsonEdit)),
                    "cod_type_wizard" => $cod_type_wizard,
                    "cod_obj" => $id,
                    "step" => 1,
                    "actual_link" => "#"
                ));
                $activity = new ActivitiesOBJ();
                $activity->set(array('state' => 10));
            }
            return 0;
        }else{
            return mysqli_error(DataBase::$mysqli);
        }
    }
    public function popolateArray($fields,$objname = null)
    {
        $columns = "";
        $values = "";
        foreach($fields as $name => $value){
            if($this->isColumn($name) != false){
                
                $val = $this->formatColumn($name, $value);
                
                if($val != "nulldate"){
                    if(isset($fields["id"]) && intval($fields["id"])>0){
                        $columns .= $name.";";
                        // $values .= "'".trim($val)."';";
                        $values .= "'".trim($val)."'|@#£|";
                    }else{
                        $columns .= $name.",";
                        $values .= "'".trim($val)."',";
                    }
                    
                }
            }
        }

        if(isset($fields["id"]) && intval($fields["id"])>0){
            if($objname != null){
                $obj = $objname."OBJ";
                $oggetto = new $obj;
                $oggetto->select($fields["id"])->load();
                $oggettoOLD = $oggetto->get();
                
            }
            
            $columns = explode(";", $columns);
            // $values = explode(";", $values);
            $values = explode("|@#£|", $values);
            $q = "UPDATE ".$this->table." SET "; 
            for($i=0;$i<count($columns)-1;$i++){
                if($columns[$i] != "id") $q .= " ".$columns[$i]." = ".$values[$i]." , ";
            }
            $q = removeLastChars($q,3);
            $q .= " WHERE id = ".$fields["id"];
            
            if($objname != null){
                $jsonEdit = new stdClass();
                    $jsonEdit->new = new stdClass();
                    $jsonEdit->old = new stdClass();
                    for($i=0;$i<count($columns)-1;$i++){
                        if($columns[$i] != "id"){
                            $name = $columns[$i];
                            $value = $values[$i];
                            $value = substr($value, 1);
                            $value = substr($value, 0, strlen($value)-1);
                            if (strlen($oggettoOLD->{$name}) == 10 && count(explode("/", $oggettoOLD->{$name})) == 3) {
                                $oggettoOLD->{$name} = data_ita_eng($oggettoOLD->{$name});
                            }
                            if ($value != $oggettoOLD->{$name}) {
                                $jsonEdit->new->{$name} = $value;
                                $jsonEdit->old->{$name} = $oggettoOLD->{$name};
                            }
                        }
                    }
            }
            
        }else{
            $q = "INSERT INTO ".$this->table." (".removeLastChars($columns).") VALUES  (".removeLastChars($values).")";
        }
        // var_dump($q);die;
        $r = mysqli_query(DataBase::$mysqli,$q);
        if($r != false){
            if(isset($fields["id"]) && intval($fields["id"])>0){
                if(intval($GLOBALS['currentLoggedUser']->id)>0 && $objname != null){
                    $cod_type_wizard = 6;
                    $_GET['gobj'] = $objname;
                    $_GET['gcod_obj'] = $fields["id"];
                    $registration = new WizardRegistrationOBJ();
                    $registration->set(array(
                        'cod_user' => $GLOBALS['currentLoggedUser']->id,
                        'info' => base64_encode(json_encode($jsonEdit)),
                        "cod_type_wizard" => $cod_type_wizard,
                        "cod_obj" => $fields["id"],
                        "step" => 1,
                        "actual_link" => "#"
                    ));
                    $activity = new ActivitiesOBJ();
                    $activity->set(array('state' => 10));
                }
                
                if(intval($GLOBALS['currentLoggedAgent']->id)>0 && $objname != null){
                    $cod_type_wizard = 6;
                    $_GET['gobj'] = $objname;
                    $_GET['gcod_obj'] = $fields["id"];
                    $registration = new WizardRegistrationOBJ();
                    $registration->set(array(
                        'cod_user' => $GLOBALS['currentLoggedAgent']->id,
                        "cod_type_ticket" => 2,
                        'info' => base64_encode(json_encode($jsonEdit)),
                        "cod_type_wizard" => $cod_type_wizard,
                        "cod_obj" => $fields["id"],
                        "step" => 1,
                        "actual_link" => "#"
                    ));
                    $activity = new ActivitiesOBJ();
                    $activity->set(array('state' => 10));
                }
                
                return intval($fields["id"]);
            }else{
                $new_id = mysqli_insert_id(DataBase::$mysqli);
                if(intval($GLOBALS['currentLoggedUser']->id)>0 && $objname != null){
                    $cod_type_wizard = 7;
                    $_GET['gobj'] = $objname;
                    $_GET['gcod_obj'] = $new_id;
                    $registration = new WizardRegistrationOBJ();
                    $registration->set(array(
                        'cod_user' => $GLOBALS['currentLoggedUser']->id,
                        'info' => "",
                        "cod_type_wizard" => $cod_type_wizard,
                        "cod_obj" => $new_id,
                        "step" => 1,
                        "actual_link" => "#"
                    ));
                    $activity = new ActivitiesOBJ();
                    $activity->set(array('state' => 10));
                }
                if(intval($GLOBALS['currentLoggedAgent']->id)>0 && $objname != null){
                    $cod_type_wizard = 7;
                    $_GET['gobj'] = $objname;
                    $_GET['gcod_obj'] = $new_id;
                    $registration = new WizardRegistrationOBJ();
                    $registration->set(array(
                        'cod_user' => $GLOBALS['currentLoggedAgent']->id,
                        "cod_type_ticket" => 2,
                        'info' => "",
                        "cod_type_wizard" => $cod_type_wizard,
                        "cod_obj" => $new_id,
                        "step" => 1,
                        "actual_link" => "#"
                    ));
                    $activity = new ActivitiesOBJ();
                    $activity->set(array('state' => 10));
                }
                return $new_id;
            }
        }else{
            return mysqli_error(DataBase::$mysqli);
        }
        
    }
    
    
    
public function data_ita_eng($data){
    if(strlen($data)>0){
        if(strpos($data, "/")){
            $explode = explode("/", $data);
            if(count($explode)==3){
                return $explode[2]."-".$explode[1]."-".$explode[0];
            }else{
                return date('Y-m-d');
            }
        }else{
            $explode = explode("-", $data);
            if(count($explode)==3){
                return $data;
            }else{
                return date('Y-m-d');
            }
        }
    }else{
        return date('Y-m-d');
    }
}

public function data_eng_ita($data){
    if(strlen($data)>0){
        if(strpos($data, "-")){
            $explode = explode("-", $data);
            if(count($explode)==3){
                return $explode[2]."/".$explode[1]."/".$explode[0];
            }else{
                return date('d/m/Y');
            }
        }else{
            $explode = explode("/", $data);
            if(count($explode)==3){
                return $data;
            }else{
                return date('d/m/Y');
            }
        }
    }else{
        return date('d/m/Y');
    }
}

}
