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

function getResult($res,$col=0,$row=0){
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

function getParams(array $data){
    foreach ($data as $key => $value) {
        if(base64_encode(base64_decode($value, true)) === $value){
            $data[$key] = base64_decode($value);
        }
    }
    return $data;
}