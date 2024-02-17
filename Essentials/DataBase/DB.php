<?php
class DataBase
{
    public static $DB_host = '';
    public static $DB_user  = '';
    public static $DB_password = '';
    public static $DB_name = '';
    public static $mysqli;
    
    public static function inizialize()
    {
        DataBase::$mysqli = mysqli_connect(DataBase::$DB_host, DataBase::$DB_user, DataBase::$DB_password,DataBase::$DB_name);
        mysqli_set_charset(DataBase::$mysqli,"utf8");
    }

    public static function getResult($res,$col=0,$row=0){
        if($res!= FALSE && $res != NULL){
            $numrows = mysqli_num_rows($res);
            if ($numrows && $row <= ($numrows-1) && $row >=0){
                mysqli_data_seek($res,$row);
                $resrow = (is_numeric($col)) ? mysqli_fetch_row($res) : mysqli_fetch_assoc($res);
                if (isset($resrow[$col])){
                    return str_replace("''", "'", trim($resrow[$col]));
                }
            }
            return false;
        }
        return false;
    }

    public static function executeQueryPrepare(string $query, string $type_params, array $params) {
        $stmt = DataBase::$mysqli->prepare($query);
        if (!$stmt) {
            return false;
        }
        if (!empty($params)) {
            $stmt->bind_param($type_params, ...$params);
            if ($stmt->errno) {
                return false;
            }
        }
        $stmt->execute();
        if ($stmt->errno) {
            return false;
        }
        $stmt->close();
        return true;
    }

    public static function getResultQueryPrepare(string $query, string $type_params, array $params) {
        $stmt = mysqli_prepare(DataBase::$mysqli,$query);
        if (!$stmt) {
            return false;
        }
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt,$type_params,...$params);
            if ($stmt->errno) {
                return false;
            }
        }
        $result = mysqli_stmt_execute($stmt);
        if (!$result) {
            return false;
        }
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }
    
    public static function getResultArray($res){
        return mysqli_fetch_assoc($res);
    }
    
    public static function EnscapeSQL($str){
        return str_replace("'","''",$str);
    }
    public static function UnscapeSQL($str){
        return str_replace("''","",$str);
    }

    public static function getSearchString($name,$type,$value="",$type_sql = "text"){
        $value = ( ($type_sql == "data" && strlen($value) == 10) ? data_ita_eng($value) : $value );
        $value = ( ($type_sql == "datatime" && strlen($value) >= 10) ? data_ita_eng($value) : $value );
        $value = DataBase::EnscapeSQL($value);
        $str = "";
        $type = intval($type);
            if($type_sql == "datatime"){
                $type=2;
            }
            if($type_sql == "toggle"){
                $type=200;
            }
            if($type_sql == "filter" || $type_sql == "subfilter"){
                $type=13;
            }
            if($type == 1 && strlen($value)>0)$str .= " LIKE '%".$value."%' AND ";
            if($type == 2 && strlen($value)>0)$str .= " LIKE '".$value."%' AND ";
            if($type == 3 && strlen($value)>0)$str .= " LIKE '%".$value."' AND ";
            if($type == 4 && strlen($value)>0)$str .= " = '".$value."' AND ";
            if($type == 5 && strlen($value)>0)$str .= " > ".floatval($value)." AND ";
            if($type == 6 && strlen($value)>0)$str .= " >= ".floatval($value)." AND ";
            if($type == 7 && strlen($value)>0)$str .= " < ".floatval($value)." AND ";
            if($type == 8 && strlen($value)>0)$str .= " <= ".floatval($value)." AND ";
            if($type == 9)$str .= " = '' AND ";
            if($type == 10 && strlen($value)>0)$str .= " NOT LIKE '%".$value."%' AND ";
            if($type == 11 && strlen($value)>0)$str .= " != '".$value."' AND ";
            if($type == 12)$str .= " != '' AND ";
            if($type == 13 && strlen($value)>0)$str .= " IN (".$value.") AND ";
            if($type == 14 && strlen($value) == 10)$str .= " >= '".$value."' AND ";
            if($type == 15 && strlen($value) == 10)$str .= " <= '".$value."' AND ";
            
            if($type == 200 && strtolower($value) == "si")$str .= " = 1 AND ";
            if($type == 200 && strtolower($value) == "no")$str .= " = 0 AND ";
            
            if(strlen($str)>0){
                $str = " if(".$name." is null,'',".$name.") ".$str;
            }
        return $str;
    }
}
DataBase::$DB_host = DB_HOST;
DataBase::$DB_user = DB_USER;
DataBase::$DB_password = DB_PWD;
DataBase::$DB_name = DB_NAME;