<?php

function getApi(string $url, array $data = [], string $method = ''){
    $curl = curl_init();
    if(strlen($method) == 0 || (isset($method) && $method == 'POST')){
        if(count($data) > 0){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
    }elseif(isset($method) && $method == 'PUT'){
        curl_setopt($curl, CURLOPT_PUT, 1);
    }elseif(isset($method) && $method == 'GET' && count($data) > 0){
        $flag = strpos($url, '?') == false ? false : true;
        foreach ($data as $key => $value) {
            $separator = array_search($key,array_keys($data)) == 0 ? '?' : '&';
            $separator = $flag ? '&' : $separator;
            $url .= $separator.$key.'='.base64_encode($value);
        }
    }
    $headers = ['Content-Type: application/json','Accept: application/json,text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8','Accept-Encoding: gzip, deflate, br','Accept-Language: it-IT,it;q=0.8,en-US;q=0.5,en;q=0.3','Connection: keep-alive','Host: localhost','Sec-Fetch-Dest: document','Sec-Fetch-Mode: navigate'];
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, '	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:122.0) Gecko/20100101 Firefox/122.0');
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT , 30);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIEFILE, "./tmp/cookie.txt");
    curl_setopt($curl, CURLOPT_ENCODING, "");
    curl_setopt($curl, CURLOPT_AUTOREFERER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_MAXREDIRS, 15);
    $result = curl_exec($curl);
    if($result === false){
        throw new Exception(curl_error($curl), curl_errno($curl));
    }
    curl_close($curl);
    return $result;
}

function getParams(array $data){
    foreach ($data as $key => $value) {
        if(base64_encode(base64_decode($value, true)) === $value){
            $data[$key] = base64_decode($value);
        }
    }
    return $data;
}

function setCustomCookie(string $name, string $value, string $date,string $path = '/', string $domain = '', $secure = false, $httponly = false){
    $result = setcookie($name,$value,$date,$path,$domain,$secure,$httponly);
    if($result || (isset($_COOKIE[$name]) && strlen($_COOKIE[$name]) > 0)){
        return true;
    }
    return false;
}

function unsetCustomCookie(string $name){
    unset($_COOKIE[$name]); 
    setcookie($name,'',time()-1,'/');
    if(!isset($_COOKIE[$name])){
        return true;
    }
    return false;
}

function getDayWeek($i){
    if($i == 0)return "Domenica";
    if($i == 1)return "Lunedì";
    if($i == 2)return "Martedì";
    if($i == 3)return "Mercoledì";
    if($i == 4)return "Giovedì";
    if($i == 5)return "Venerdì";
    if($i == 6)return "Sabato";
    if($i == 7)return "Domenica";
}

function NormalizeCAP($str){
    if(strlen($str)<5){
        $ns = "";
        for($k=0;$k<intval(5-strlen($str));$k++){
            $ns .= "0";
        }
        $str = $ns.$str;
    }
    return $str;
}

function checkEmail($email){
	// Elimino spazi, "a capo" e altro alle estremità della stringa
	$email = trim($email);
	// Se la stringa è vuota sicuramente non è una mail
	if(!$email) {
		return false;
	}
	// Controllo che ci sia una sola @ nella stringa
	$num_at = count(explode( '@', $email )) - 1;
	if($num_at != 1) {
		return false;
	}
	// Controllo la presenza di ulteriori caratteri "pericolosi":
	if(strpos($email,';') || strpos($email,',') || strpos($email,' ')) {
		return false;
	}
	// La stringa rispetta il formato classico di una mail?
	if(!preg_match( '/^[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}$/', $email)) {
		return false;
	}
	return true;
}

function checkCodFis($cf){
    if($cf==''){
        return false;
    }

    if(strlen($cf)!= 16){
        return false;
    }

    $cf=strtoupper($cf);
    if(!preg_match("/[A-Z0-9]+$/", $cf)){
        return false;
    }

    $s = 0;
    for($i=1; $i<=13; $i+=2){
        $c=$cf[$i];
        if('0'<=$c and $c<='9'){
            $s+=ord($c)-ord('0');
        }else{
            $s+=ord($c)-ord('A');
        }
    }

    for($i=0; $i<=14; $i+=2){
        $c=$cf[$i];
        switch($c){
            case '0':  $s += 1;  break;
            case '1':  $s += 0;  break;
            case '2':  $s += 5;  break;
            case '3':  $s += 7;  break;
            case '4':  $s += 9;  break;
            case '5':  $s += 13;  break;
            case '6':  $s += 15;  break;
            case '7':  $s += 17;  break;
            case '8':  $s += 19;  break;
            case '9':  $s += 21;  break;
            case 'A':  $s += 1;  break;
            case 'B':  $s += 0;  break;
            case 'C':  $s += 5;  break;
            case 'D':  $s += 7;  break;
            case 'E':  $s += 9;  break;
            case 'F':  $s += 13;  break;
            case 'G':  $s += 15;  break;
            case 'H':  $s += 17;  break;
            case 'I':  $s += 19;  break;
            case 'J':  $s += 21;  break;
            case 'K':  $s += 2;  break;
            case 'L':  $s += 4;  break;
            case 'M':  $s += 18;  break;
            case 'N':  $s += 20;  break;
            case 'O':  $s += 11;  break;
            case 'P':  $s += 3;  break;
            case 'Q':  $s += 6;  break;
            case 'R':  $s += 8;  break;
            case 'S':  $s += 12;  break;
            case 'T':  $s += 14;  break;
            case 'U':  $s += 16;  break;
            case 'V':  $s += 10;  break;
            case 'W':  $s += 22;  break;
            case 'X':  $s += 25;  break;
            case 'Y':  $s += 24;  break;
            case 'Z':  $s += 23;  break;
        }
    }

    if(chr($s%26+ord('A'))!=$cf[15]){
        return false;
    }

    return true;
}

function xorString($str,$key = ""){
    $key = ('Yf7PuE7xNtddEJ7g5KaD7Jn3SJW6wQSRFrB'.$key);
    $text = $str;
    $outText = '';
    for($i=0; $i<strlen($text);){
        for($j=0; ($j<strlen($key) && $i<strlen($text)); $j++,$i++){
            $outText .= $text[$i] ^ $key[$j];
        }
    }
    return $outText;
}

function encryptString($string,$key=""){
    return base64_encode(xorString($string,$key));
}

function decryptString($string,$key=""){
    return xorString(base64_decode($string),$key);
}

function generateAlpanumericToken($length = 30) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $count = mb_strlen($chars);
    
    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }
    
    return $result;
}

function generateNumericToken($length = 30) {
    $chars = '0123456789';
    $count = mb_strlen($chars);
    
    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }
    
    return $result;
}

function getMonthLong($mese){
    if(intval($mese)==1)    return "Gennaio";
    if(intval($mese)==2)    return "Febbraio";
    if(intval($mese)==3)    return "Marzo";
    if(intval($mese)==4)    return "Aprile";
    if(intval($mese)==5)    return "Maggio";
    if(intval($mese)==6)    return "Giugno";
    if(intval($mese)==7)    return "Luglio";
    if(intval($mese)==8)    return "Agosto";
    if(intval($mese)==9)    return "Settembre";
    if(intval($mese)==10)   return "Ottobre";
    if(intval($mese)==11)   return "Novembre";
    if(intval($mese)==12)   return "Dicembre";
}

function getMonthShort($mese){
    if(intval($mese)==1)    return "Gen";
    if(intval($mese)==2)    return "Feb";
    if(intval($mese)==3)    return "Mar";
    if(intval($mese)==4)    return "Apr";
    if(intval($mese)==5)    return "Mag";
    if(intval($mese)==6)    return "Giu";
    if(intval($mese)==7)    return "Lug";
    if(intval($mese)==8)    return "Ago";
    if(intval($mese)==9)    return "Set";
    if(intval($mese)==10)   return "Ott";
    if(intval($mese)==11)   return "Nov";
    if(intval($mese)==12)   return "Dic";
}