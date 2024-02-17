<?php
class Settings extends DBObject
{
    public $code = "";
    public function __construct() {
        parent::__construct('settings');
        $q = "SELECT * FROM settings WHERE id = 1";
        $r = mysqli_query(DataBase::$mysqli,$q);
        $this->id_user = DataBase::getResult($r,"id_user");
        // $this->code = DataBase::getResult($r,"code");
        // $this->piva = DataBase::getResult($r,"piva");
        // $this->ragsoc = DataBase::getResult($r,"ragsoc");
        // $this->toponimo = DataBase::getResult($r,"toponimo");
        // $this->indirizzo = DataBase::getResult($r,"indirizzo");
        // $this->civico = DataBase::getResult($r,"civico");
        // $this->cap = DataBase::getResult($r,"cap");
        // $this->comune = DataBase::getResult($r,"comune");
        // $this->provincia = DataBase::getResult($r,"provincia");
        // $this->nazione = DataBase::getResult($r,"nazione");
        // $this->toponimo_op = DataBase::getResult($r,"toponimo_op");
        // $this->indirizzo_op = DataBase::getResult($r,"indirizzo_op");
        // $this->civico_op = DataBase::getResult($r,"civico_op");
        // $this->cap_op = DataBase::getResult($r,"cap_op");
        // $this->comune_op = DataBase::getResult($r,"comune_op");
        // $this->provincia_op = DataBase::getResult($r,"provincia_op");
        // $this->nazione_op = DataBase::getResult($r,"nazione_op");
        // $this->descSedi = DataBase::getResult($r,"descSedi");
        // $this->REA_ufficio = DataBase::getResult($r,"REA_ufficio");
        // $this->REA_code = DataBase::getResult($r,"REA_code");
        // $this->REA_capitale_sociale = DataBase::getResult($r,"REA_capitale_sociale");
        // $this->REA_SocioUnico = DataBase::getResult($r,"REA_SocioUnico");
        // $this->REA_StatoLiquidazione = DataBase::getResult($r,"REA_StatoLiquidazione");
        // $this->telefono = DataBase::getResult($r,"telefono");
        // $this->whatsapp = DataBase::getResult($r,"whatsapp");
        // $this->fax = DataBase::getResult($r,"fax");
        // $this->email = DataBase::getResult($r,"email");
        // $this->pec = DataBase::getResult($r,"pec");
        // $this->rappresentante = DataBase::getResult($r,"rappresentante");
        // $this->rappresentante_luogo_nascita = DataBase::getResult($r,"rappresentante_luogo_nascita");
        // $this->rappresentante_data_nascita = DataBase::getResult($r,"rappresentante_data_nascita");
        // $this->rappresentante_indirizzo = DataBase::getResult($r,"rappresentante_indirizzo");
        // $this->rappresentante_codfis = DataBase::getResult($r,"rappresentante_codfis");
        // $this->RegimeFiscale = DataBase::getResult($r,"RegimeFiscale");
        // $this->sitoweb = DataBase::getResult($r,"sitoweb");
        // $this->pec_host = DataBase::getResult($r,"pec_host");
        // $this->pec_user = DataBase::getResult($r,"pec_user");
        // $this->pec_pwd = DataBase::getResult($r,"pec_pwd");
        // $this->mail_host = DataBase::getResult($r,"mail_host");
        // $this->mail_user = DataBase::getResult($r,"mail_user");
        // $this->mail_pwd = DataBase::getResult($r,"mail_pwd");
        // $this->skebby_sender = DataBase::getResult($r,"skebby_sender");
        // $this->skebby_user = DataBase::getResult($r,"skebby_user");
        // $this->skebby_pwd = DataBase::getResult($r,"skebby_pwd");
    } 
    
    public function set($params,$returnError = false,$blockInsert = false){
        $params['id'] = 1;
        $params['code'] = $GLOBALS['GENERAL_SETTINGS']->code;
        
        if($blockInsert == false){$message = $this->popolateArray($params,$this->objectName);}
        return $message;
    }
    

}