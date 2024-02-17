<?php
class UserOBJ extends DBObject
{
    public $objectName = "User";
    public $icon = "user";
    public $label = "Utenti";
    public $filterColor = "orange";
    public $filterColumn = "name";
    public $filterExernal = "cod_user";

    
    public function __construct() {
	    parent::__construct('user');
        $this->noShowFields = array();
        $this->TagLogName = "User";
    } 
    
    public function getListView($currentLoggedUser){
        $viewObj = new stdClass();
        $viewObj->singlepage = "single.php?object=".$this->objectName."&id=";
        $viewObj->apiPage = "apiGeneric.php?table=".$this->table."&object=".$this->objectName."OBJ";
        $viewObj->titlePage = "Utenti";
        $viewObj->hasExport = true;
        $viewObj->hasAdd = true;
        $viewObj->linkAdd = ADMINURL."edit.php?object=".$this->objectName;
        $viewObj->hasOverCol = false;
        $viewObj->fields = array(
            (object)['name'=>"name","label"=>"Nome","columns" => "6","type"=>"text"],
            (object)['name'=>"surname","label"=>"Cognome","columns" => "6","type"=>"text"],
            (object)['name'=>"email","label"=>"Email","columns" => "6","type"=>"text"],
            (object)['name'=>"linkInNewTab","label"=>"Apri Link in un nuovo TAB","columns" => "6","type"=>"toggle"],
        );
        $viewObj->exportList = array(
            (object)['name'=>"name","label"=>"Nome","value" => "1","type"=>"text"],
            (object)['name'=>"surname","label"=>"Cognome","value" => "1","type"=>"text"],
            (object)['name'=>"email","label"=>"Email","value" => "1","type"=>"text"],
        );
        return $viewObj;
    }
    
    public function set($params,$returnError = false,$blockInsert = false){
        $message = '';
        if(
            strlen($params['name']) &&
            strlen($params['surname']) &&
            strlen($params['email'])
            ){
            $message = $this->popolateArray($params,$this->objectName);
        }else{
            if(!strlen($params['name'])){
                $message = 'Il nome è obbligatorio!';
            }
            if(!strlen($params['surname'])){
                $message = 'Il cognome è obbligatorio!';
            }
            if(!strlen($params['email'])){
                $message = 'La Mail è obbligatoria!';
            }

        }
        return $message;
    }
    
    public function login($email,$pwd){
        $state = 0;
        $q = "SELECT id FROM user WHERE email = '".$email."' AND pwd = '".$pwd."' AND enable = 1";
        $r = mysqli_query(KikkoDB::$mysqli, $q);
        if(mysqli_num_rows($r)>0){
            $state = KikkoDB::getResult($r,"id");
        }
        return $state;
    }
    
    
    public function resetPWD($id){
        if(!userCanAccessArea($GLOBALS['currentLoggedUser']->id, array())){
            ?><script>window.location.href="index.php";</script><?php
        }

        $userOBJ = new UserOBJ;
        $userOBJ->select($id)->load();
        $utente = $userOBJ->get();

        $pwd = generateToken(20);

        $pino = $userOBJ->set([
            'email' =>$utente->email,
            'name' =>$utente->name,
            'surname' =>$utente->surname,
            'pwd' => md5($pwd)
        ]);

        dd($pino);

        // $q = "UPDATE user SET pwd='". md5($pwd)."' WHERE id=".$id;
        // $r = mysqli_query(KikkoDB::$mysqli, $q);

        die;
        // $postparam = array(
        //     "denominazione" => $user->name." ".$user->surname,
        //     "pwd" => $pwd,
        //     "link" => DOMAIN."administrator/" 
        // );
        // $html  = getPostPagePDF(DOMAIN."dem/demPassword.php",$postparam);
        // sendMailStd($user->email,"Nuova Password - Gemaca",$html);
    }
    
    public function getView($id = 0,$currentLoggedUser = null){
        
        $this->select($id)->load();
        $obj = $this->get();
        
        $viewObj = new stdClass();
        $viewObj->title = $this->getProperty($obj,"name")." ".$this->getProperty($obj,"surname");
        $viewObj->subtitle = $this->getProperty($obj,"email");
        $viewObj->firstLetterInTitle = firstLetterOfSentences($this->getProperty($obj,"name")." ".$this->getProperty($obj,"surname"));
        $viewObj->backlink = ADMINURL."listObject.php?objects=User";
        if($id == 0){
            $viewObj->title = 'Nuovo Utente';
            $viewObj->subtitle = "";
            $viewObj->firstLetterInTitle = "";
            $viewObj->backlinkEdit = ADMINURL."listObject.php?objects=User";
        }else{
            $viewObj->backlinkEdit = ADMINURL."single.php?object=".$this->objectName."&id=".$id;
        }
        
        if(intval(getGet("resetpwd")) && $id > 0){
            $this->resetPWD($id);
            ?><script>window.location.href="<?php echo $viewObj->backlinkEdit;?>";</script><?php
        }
        
        
            $viewObj->editHtml = "";

            
            $viewObj->bigSections = array();
            $sections = array();

            $section = new stdClass();
            $section->title = "Dati Generali";
            $section->icon = "la-circle-o-notch";
            $section->col = "8";
            $section->type = "fields";
            $section->inEditView = true;
            $section->inView = true;
            //$section->viewLink = (userCanAccessArea($currentLoggedUser->id, array(1))) ? ADMINURL."utils/usergraph.php?id=".$id : "";
            $section->pwdLink = (userCanAccessArea($currentLoggedUser->id, array(1))) ? ADMINURL."edit.php?resetpwd=1&object=".$this->objectName."&id=".$id : "";
            $section->editLink = (userCanAccessArea($currentLoggedUser->id, array(1))) ? ADMINURL."edit.php?object=".$this->objectName."&id=".$id : "";
            $section->fields = array(
                (object)["inView"=>1,"inEditView"=>1,"name"=>"name","label"=>"Nome","col"=>"4","type"=>"text","value"=>$this->getProperty($obj, "name")],
                (object)["inView"=>1,"inEditView"=>1,"name"=>"surname","label"=>"Cognome","col"=>"4","type"=>"text","value"=>$this->getProperty($obj, "surname")],
                (object)["inView"=>1,"inEditView"=>1,"name"=>"email","label"=>"Email","col"=>"4","type"=>"text","value"=>$this->getProperty($obj, "email")],
                (object)['inView'=>1,'inEditView'=>1,'name'=>'cod_color','label'=>'Marca','col'=>'12','type'=>'select',"object"=>"ColoreOBJ","object_name"=>"name",'extraQuery' => "",'value'=>$this->getProperty($obj, 'cod_color')],
                

                (object)["inView"=>1,"inEditView"=>1,"name"=>"linkInNewTab","label"=>"Apri Link in Nuova Pagina","col"=>"6","type"=>"toggle","value"=>$this->getProperty($obj, "linkInNewTab")]
            );
            array_push($sections, $section);

            

            /* RUOLI */

            $section = new stdClass();
            $section->title = "Ruoli";
            $section->icon = "la-users";
            $section->col = "4";
            $section->type = "table";
            $section->inEditView = false;
            $section->inView = true;
            $section->plusLink = (userCanAccessArea($currentLoggedUser->id, array(1))) ? ADMINURL."edit.php?object=UserArea&cod_user=".$id : "";
            $section->fields = array(
                    (object)['name'=>'name','label'=>'Nome'],
                    (object)['name'=>'admin_area','label'=>'Admin dell\'Area'],
                    (object)['name'=>'azione','label'=>'Azione']
            );
            $section->rows = array();
            $risultati = $this->getRuoli($id);
            // dd($risultati);
            foreach($risultati as $risultato){
                $tableobj = new stdClass();
                $tableobj->name = (object)['value'=>$risultato->name];
                $tableobj->admin_area = (object)['value'=>$risultato->admin_area];
                $tableobj->azione = (object)['value'=>'<i class="text-red la la-close"></i>','link'=>ADMINURL."edit.php?object=UserArea&delete=1&cod_user=".$id."&id=".$risultato->id];
                array_push($section->rows, $tableobj);
            }
            array_push($sections, $section);

            //ALLEGATI
            $section = new stdClass();
            $section->title = "Allegati";
            $section->icon = "la-file-pdf";
            $section->col = "12";
            $section->type = "allegato";
            $section->inEditView = false;
            $section->inView = true;
            // (userCanAccessArea($currentLoggedUser->id, array(1))) ? ADMINURL."edit.php?object=UserArea&cod_user=".$id : "";
            $section->plusLink = userCanAccessArea($currentLoggedUser->id, 5) ? ADMINURL."add_allegato.php?cod_obj=" . $id . "&obj=User" : false;
            $section->fields = array(
                (object)['name' => 'name', 'label' => 'Nome'],
                (object)['name' => 'type', 'label' => 'Tipo'],
                (object)['name' => 'azione', 'label' => 'Azione']
            );
            $section->rows = array();
            $risultati = $this->getAllegati($id);
            foreach ($risultati as $risultato) {
                array_push($section->rows, $risultato);
            }
            array_push($sections, $section);

            
            /* Aree Task */

            // $section = new stdClass();
            // $section->title = "Aree Task";
            // $section->icon = "la-users";
            // $section->col = "12";
            // $section->type = "table";
            // $section->inEditView = false;
            // $section->inView = true;
            // $section->editLink = (userCanAccessArea($currentLoggedUser->id, array())) ? ADMINURL."calendar/add_user_jobs.php?cod_user=".$id : "";
            // $section->fields = array(
            //         (object)['name'=>'name','label'=>'Nome']
            // );
            // $section->rows = array();
            // $risultati = $this->getCategoryJobs($id);
            // foreach($risultati as $risultato){
            //     $tableobj = new stdClass();
            //     $tableobj->name = (object)['value'=>$risultato->name];
            //     array_push($section->rows, $tableobj);
            // }
            // array_push($sections, $section);
            
            
            $bigSection = new stdClass();
            $bigSection->inEditView = true;
            $bigSection->inView = true;
            $bigSection->col = "12";
            $bigSection->sections = $sections;
            array_push($viewObj->bigSections, $bigSection);
            

        
        return $viewObj;
    }

    public function getAreeCompetenza($id){
        $q = "SELECT area_competenza_user.id,type_area_competenza.name as name "
                . "FROM area_competenza_user,type_area_competenza "
                . "WHERE type_area_competenza.id = area_competenza_user.cod_area_competenza AND "
                . "area_competenza_user.cod_user = '".$id."' "
                . "ORDER BY name DESC";
        $r = mysqli_query(DataBase::$mysqli, $q);
        $array = array();
        for($i=0;$i<mysqli_num_rows($r);$i++){
            $o = new stdClass();
            $o->id = DataBase::getResult($r,"id",$i);
            $o->name = DataBase::getResult($r,"name",$i);
            array_push($array, $o);
        }
        return $array;
    }
   
    public function getRuoli($id){
        $q = "SELECT user_area.id,user_area.admin_area,type_area.name as name "
                . "FROM user_area,type_area "
                . "WHERE type_area.id = user_area.cod_area AND "
                . "user_area.cod_user = '".$id."' "
                . "ORDER BY name DESC";
        // dd($q);
        $r = mysqli_query(KikkoDB::$mysqli, $q);
        $array = array();
        for($i=0;$i<mysqli_num_rows($r);$i++){
            $o = new stdClass();
            $o->id = DataBase::getResult($r,"id",$i);
            $o->name = DataBase::getResult($r,"name",$i);
            $o->admin_area = DataBase::getResult($r,"admin_area",$i) ? "SI" : "NO";
            array_push($array, $o);
        }
        return $array;
    }
    
    public function getProperty($obj,$name,$default = ""){
        switch ($name){
            
            case "nomeCompleto":
                $txt = $obj->name." ".$obj->surname;
               
                break;
            case "pippo":
                $txt = $obj->name." ".$obj->surname;
               
                break;
            default :
                $txt = $obj->{$name};
        }
        $txt = trim($txt);
        if(strlen($txt)>0){
            return $txt;
        }else{
            return $default;
        }
    }

    public function getAllegati($id) {
        $q = "SELECT allegati.id,allegati.name,type_allegato.name as type, date_creation, note "
                . "FROM allegati,type_allegato "
                . "WHERE type_allegato.id = allegati.cod_type_allegato AND "
                . "obj = 'User' AND "
                . "cod_obj = '" . $id . "' "
                . "ORDER BY date_creation DESC";
        $r = mysqli_query(DataBase::$mysqli, $q);
        $array = array();
        for ($i = 0; $i < mysqli_num_rows($r); $i++) {
            $o = new stdClass();
            $o->id = DataBase::getResult($r, "id", $i);
            $o->name = DataBase::getResult($r, "name", $i);
            $o->type = DataBase::getResult($r, "type", $i);
            $o->date_creation = DataBase::getResult($r, "date_creation", $i);
            $o->note = DataBase::getResult($r, "note", $i);
            array_push($array, $o);
        }
        return $array;
    }

    public static function dammiTutto($id = 0){
        if(intval($id)){
            $userOBJ = new UserOBJ;
            $userOBJ->select($id)->load();
            return $userOBJ->get();
        }else{
            return [];
        }
    }

    
}