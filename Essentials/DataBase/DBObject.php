<?php
class DBObject extends stdClass
{
    public $table;
    protected $fields;
    public $noShowFields = array();
    public $tempQuery = "";
    public $tempResult = false;
    public $hasResult = false;
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
                while ($fieldinfo = mysqli_fetch_field($r)){
                    $f = new stdClass();
                    $f->name = $fieldinfo->name;
                    $f->type = $fieldinfo->type;
                    array_push($this->fields, $f);
                }
                $this->primarykey = ($this->isColumn("id")) ? "id" : "code";
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
            $isset = (strlen($_POST[$name]) > 0) ? $_POST[$name] : $_GET[$name];
        }
        if($isset == "id"){
            return "";
        }
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
                        $formatted = ($isOutput && strlen($value) == 19) ? data_eng_ita(explode(" ", $value)[0])." ". explode(":", explode(" ", $value)[1])[0].":".explode(":", explode(" ", $value)[1])[1] : $value;
                        break;
                    case MYSQLI_TYPE_DATE:
                        if(strlen($value) == 10){
                            $formatted = ($isOutput) ? data_eng_ita($value) : data_ita_eng($value);
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
    
    public function load(){
        $this->tempResult = DataBase::getQuery($this->tempQuery);
        if($this->tempResult == false){
            $this->hasResult = false;
        }else{
            if(mysqli_num_rows($this->tempResult)>0){
                $this->hasResult = true;
            }else{
                $this->hasResult = false;
            }
        }
    }

    public function get($id = 0){
        $obj = new stdModel;
        foreach($this->fields as $field){
            if(!in_array($field->name, $this->noShowFields)){
                $value = ($this->hasResult != false) ? DataBase::getResult($this->tempResult,$field->name,$id) : "";
                $obj->{$field->name} = $this->formatColumn($field->name,$value,true);
            }            
        }
        return $obj;
    }
    
    public function popolateArray($fields,$objname = null){
        $columns = "";
        $values = "";
        foreach($fields as $name => $value){
            if($this->isColumn($name) != false){
                
                $val = $this->formatColumn($name, $value);
                
                if($val != "nulldate"){
                    if(isset($fields["id"]) && intval($fields["id"])>0){
                        $columns .= $name.";";
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
        $r = mysqli_query(DataBase::$mysqli,$q);
        if($r != false){
        }else{
            return mysqli_error(DataBase::$mysqli);
        }
    }

    public static function getData($q_in = "",$table,$fields,$replace_list = [],$where_conditions = [], $group_by = [], $order_by = [], $limit = ""){
        if(strlen($q_in) == 0){
            $q = "SELECT ";
            foreach ($fields as $key => $field){
                if(isset($replace_list[$field->name])){
                    $field = rtrim($replace_list[$field->name]);
                    if(substr($field, -1) == ','){
                        $field = substr($field, 0, -1);
                    }
                    $q .= $field;
                }else{
                    $q .= " $field->name";
                }
                if($key != count($fields) - 1){
                    $q .= ", ";
                }else{
                    $q .= " ";
                }
            }
            $q .= " FROM $table ";
            if(count($where_conditions) > 0){
                $q .= " WHERE ";
                foreach($where_conditions as $key => $condition){
                    if(is_array($condition)){
                        $q .= " (";
                        foreach ($condition as $key_2 => $object) {
                            if(is_object($object)){
                                $is_last = $key_2 == count($condition) - 1 ? ")" : "";
                                $q .= " (".$object->string.")  $is_last $object->condition ";
                            }
                        }
                    }elseif(is_object($condition)){
                        $object_condition = isset($condition->condition) ? $condition->condition : "";
                        $q .= " $condition->string $object_condition ";
                    }else{
                        $separator = $key == count($where_conditions) - 1 ? "" : "AND";
                        $q .= " $condition $separator ";
                    }
                }
            }
            if(count($group_by) > 0){
                $q .= " GROUP BY ";
                foreach($group_by as $key => $group){
                    $comma = $key == count($group_by) - 1 ? "" : ",";
                    $q .= " $group"."$comma ";
                }
            }
            if(count($order_by) > 0){
                $q .= " ORDER BY ";
                foreach($order_by as $key => $order){
                    if(is_object($order)){
                        $comma = $key == count($order_by) - 1 ? "" : ",";
                        $q .= " $order->string $order->condition"."$comma ";
                    }
                }
            }
            if(strlen($limit) > 0){
                $q .= " $limit";
            }else{
                $q .= " LIMIT 600";
            }
        }else{
            $q = $q_in;
        }
        $r = mysqli_query(DataBase::$mysqli, $q);
        if(mysqli_num_rows($r) > 0){
            $data = [];
            for($i = 0; $i < mysqli_num_rows($r); $i++){
                $array = DataBase::getResultArray($r,$i);
                $row = [];
                foreach ($array as $key => $value) {
                    foreach ($fields as $object) {
                        if($object->name == $key){
                            array_push($row, (object)['value' => $value, 'print' => $object->print]);
                        }
                    }
                }
                array_push($data,$row);
            }
        }
        return $data;
    }
}
