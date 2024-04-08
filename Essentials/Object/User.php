<?php
class UserOBJ extends DBObject
{
    public $objectName = "User";
    public $icon = "user";
    public $label = "Utenti";
    public $filterColor = "orange";
    public $filterColumn = "name";
    public $filterExernal = "email";


    public function __construct(){
        parent::__construct('users');
        $this->noShowFields = array();
        $this->TagLogName = "User";
    }

    public function getListView($currentLoggedUser){
        $viewObj = new stdClass();
        $viewObj->singlePage = "?page=detail&object=" . $this->objectName . "&id=";
        $viewObj->titlePage = "Utenti";
        $viewObj->hasAdd = true;
        $viewObj->linkAdd = ADMINPATH . "?page=edit&object=" . $this->objectName;
        $viewObj->buttons = array(
            (object)["label" => "Aggiungi", "icon" => "plus", "button_class" => "confirm btn-small", "link" => DOMAIN."?page=edit&object=".$this->objectName]
        );
        $viewObj->fields = array(
            (object)['name' => "id", "label" => "ID", "col" => "0", "type" => "text", "type_column" => "int", "print" => 0],
            (object)['name' => "name", "label" => "Nome", "col" => "3", "type" => "text", "type_column" => "varchar", "print" => 1],
            (object)['name' => "surname", "label" => "Cognome", "col" => "3", "type" => "text", "type_column" => "varchar", "print" => 1],
            (object)['name' => "email", "label" => "Email", "col" => "3", "type" => "text", "type_column" => "varchar", "print" => 1],
            (object)['name' => "is_admin", "label" => "Admin", "col" => "3", "type" => "text", "type_column" => "varchar", "print" => 1]
        );
        $replace = ["name" => "(SELECT name FROM user_info WHERE user_info.id = id_user_info) AS name", "surname" => "(SELECT surname FROM user_info WHERE user_info.id = id_user_info) AS surname","is_admin" => "IF(is_admin = 1, 'SI', 'NO') AS is_admin"];
        $where_q = ["is_admin != 0"];
        $where = [
            [
                (object)["string" => "name LIKE '%z%'", "condition" => "OR"],
                (object)["string" => "name = 'Pierdomenico'", "condition" => "AND"]
            ],
            (object)["string" => "pwd IS NOT NULL", "condition" => "AND"],
            "email IS NOT NULL"
        ];
        $group_by = [
            "id",
            "name"
        ];
        $order_by = [
            (object)["string" => "id", "condition" => "DESC"]
        ];
        $viewObj->datas = $this->getData("",$this->table,$viewObj->fields,$replace,[]);
        $viewObj->query = $this->getData("",$this->table,$viewObj->fields,$replace,[],[],[],"","query");
        $viewObj->replace = $replace;
        $viewObj->table = $this->table;
        return $viewObj;
    }

    public function set($params, $returnError = false, $blockInsert = false){
        $message = '';
        if (strlen($params['email'])) {
            $message = $this->popolateArray($params, $this->objectName);
        } else {
            if (!strlen($params['email'])) {
                $message = 'La mail è obbligatoria!';
            }
        }
        return $message;
    }

    // public function login($email, $pwd)
    // {
    //     $state = 0;
    //     $q = "SELECT id FROM user WHERE email = '" . $email . "' AND pwd = '" . $pwd . "' AND enable = 1";
    //     $r = mysqli_query(KikkoDB::$mysqli, $q);
    //     if (mysqli_num_rows($r) > 0) {
    //         $state = KikkoDB::getResult($r, "id");
    //     }
    //     return $state;
    // }


    // public function resetPWD($id){
        // if (!userCanAccessArea($GLOBALS['currentLoggedUser']->id, array())) {
        // <script>window.location.href = "index.php";</script>
        // }

        // $userOBJ = new UserOBJ;
        // $userOBJ->select($id)->load();
        // $utente = $userOBJ->get();

        // $pwd = generateToken(20);

        // $pino = $userOBJ->set([
        //     'email' => $utente->email,
        //     'name' => $utente->name,
        //     'surname' => $utente->surname,
        //     'pwd' => md5($pwd)
        // ]);

        // dd($pino);

        // $q = "UPDATE user SET pwd='". md5($pwd)."' WHERE id=".$id;
        // $r = mysqli_query(KikkoDB::$mysqli, $q);

        // die;
        // $postparam = array(
        //     "denominazione" => $user->name." ".$user->surname,
        //     "pwd" => $pwd,
        //     "link" => DOMAIN."administrator/" 
        // );
        // $html  = getPostPagePDF(DOMAIN."dem/demPassword.php",$postparam);
        // sendMailStd($user->email,"Nuova Password - Gemaca",$html);
    // }

    public function getView($id = 0, $currentLoggedUser = null){
        $this->select($id)->load();
        $obj = $this->get();

        $viewObj = new stdClass();
        $viewObj->title = $this->getProperty($obj, "name") . " " . $this->getProperty($obj, "surname");
        $viewObj->subtitle = $this->getProperty($obj, "email");
        $viewObj->backlink = DOMAIN . "?page=obj&objects=". $this->objectName;
        if ($id == 0) {
            $viewObj->title = 'Nuovo Utente';
            $viewObj->subtitle = "";
            $viewObj->firstLetterInTitle = "";
            $viewObj->backlinkEdit = DOMAIN . "?page=obj&objects=". $this->objectName;
        } else {
            $viewObj->backlinkEdit = DOMAIN . "?page=detail&object=" . $this->objectName . "&id=" . $id;
        }

        //         if (intval(getGet("resetpwd")) && $id > 0) {
        //             $this->resetPWD($id);
        //             ?><script>
        //     window.location.href = "<?php echo $viewObj->backlinkEdit; ?>";
        // </script><?php
        //         }

        $viewObj->editHtml = "";
        $viewObj->bigSections = array();
        $sections = array();

        $section = new stdClass();
        $section->title = "Dati Generali";
        $section->icon = "la-circle-o-notch";
        $section->col = "12";
        $section->type = "fields";
        $section->visible_edit = true;
        $section->visible = true;
        $section->buttons = array(
            (object)["label" => "Modifica", "icon" => "pencil", "button_class" => "danger", "link" => DOMAIN."?page=edit&object=".$this->objectName."&id=".$id]
        );
        $section->fields = array(
            (object)["visible"=>1,"visible_edit"=>1,"name"=>"name","label"=>"Nome","col"=>"4","type"=>"text","value"=>$this->getProperty($obj, "name")],
            (object)["visible"=>1,"visible_edit"=>1,"name"=>"surname","label"=>"Cognome","col"=>"4","type"=>"text","value"=>$this->getProperty($obj, "surname")],
            (object)["visible"=>1,"visible_edit"=>1,"name"=>"email","label"=>"Email","col"=>"4","type"=>"text","value"=>$this->getProperty($obj, "email")]
        );
        array_push($sections, $section);

        $section = new stdClass();
        $section->title = "Sessioni";
        $section->icon = "la-circle-o-notch";
        $section->col = "6";
        $section->type = "fields";
        $section->visible_edit = false;
        $section->visible = true;
        $results = $this->getSessions($id);
        $section->fields = array();
        foreach ($results as $result) {
            array_push($section->fields, $result);
        }
        array_push($sections, $section);
        $bigSection = new stdClass();
        $bigSection->inEditView = true;
        $bigSection->inView = true;
        $bigSection->col = "12";
        $bigSection->sections = $sections;
        array_push($viewObj->bigSections, $bigSection);

        return $viewObj;
    }

    public function getProperty($obj, $name, $default = ""){
        switch ($name) {
            case "nomeCompleto":
                $txt = $obj->name . " " . $obj->surname;

                break;
            case "pippo":
                $txt = $obj->name . " " . $obj->surname;
                break;
            default:
                $txt = $obj->{$name};
        }
        $txt = trim($txt);
        if (strlen($txt) > 0) {
            return $txt;
        } else {
            return $default;
        }
    }

    public function getSessions($id){
        $results = [];
        if(strlen($id) > 0){
            $q = "SELECT * FROM sessions_users WHERE id_user = $id";
            $r = mysqli_query(DataBase::$mysqli, $q);
            if(mysqli_num_rows($r) > 0){
                for ($i = 0; $i < mysqli_num_rows($r); $i++) { 
                    array_push($results, (object)["visible"=>1,"visible_edit"=>1,"name"=>"session_start","label"=>"Inizio Sessione","col"=>"4","type"=>"text","value"=> DataBase::getResult($r, 'session_start', $i)]);
                    array_push($results, (object)["visible"=>1,"visible_edit"=>1,"name"=>"session_end","label"=>"Fine Sessione","col"=>"4","type"=>"text","value"=> DataBase::getResult($r, 'session_end', $i)]);
                }
            }
        }

        return $results;
    }
}