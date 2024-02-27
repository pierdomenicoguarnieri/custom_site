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
        $viewObj->singlePage = "?page=detail&objects=User" . $this->objectName . "&id=";
        $viewObj->titlePage = "Utenti";
        $viewObj->hasAdd = true;
        $viewObj->linkAdd = ADMINPATH . "?page=edit&object=" . $this->objectName;
        $viewObj->fields = array(
            (object)['name' => "id", "label" => "ID", "columns" => "6", "type" => "text", "print" => 0],
            (object)['name' => "name", "label" => "Nome", "columns" => "6", "type" => "text", "print" => 1],
            (object)['name' => "surname", "label" => "Cognome", "columns" => "6", "type" => "text", "print" => 1],
            (object)['name' => "email", "label" => "Email", "columns" => "6", "type" => "text", "print" => 1],
            (object)['name' => "is_admin", "label" => "Admin", "columns" => "6", "type" => "text", "print" => 1],
        );
        $replace = ["is_admin" => "IF(is_admin = 1, 'SI', 'NO') AS is_admin,"];
        // $where = [
        //     [
        //         (object)["string" => "name LIKE '%z%'", "condition" => "OR"],
        //         (object)["string" => "name = 'Pierdomenico'", "condition" => "AND"]
        //     ],
        //     (object)["string" => "pwd IS NOT NULL", "condition" => "AND"],
        //     "email IS NOT NULL"
        // ];
        // $group_by = [
        //     "id",
        //     "name"
        // ];
        // $order_by = [
        //     (object)["string" => "id", "condition" => "DESC"]
        // ];
        $viewObj->datas = $this->getData("",$this->table,$viewObj->fields,$replace);
        return $viewObj;
    }

    public function set($params, $returnError = false, $blockInsert = false){
        $message = '';
        if (strlen($params['name']) && strlen($params['surname']) && strlen($params['email'])) {
            $message = $this->popolateArray($params, $this->objectName);
        } else {
            if (!strlen($params['name'])) {
                $message = 'Il nome è obbligatorio!';
            }
            if (!strlen($params['surname'])) {
                $message = 'Il cognome è obbligatorio!';
            }
            if (!strlen($params['email'])) {
                $message = 'La Mail è obbligatoria!';
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
        $viewObj->backlink = DOMAIN . "?page=obj&objects=User";
        if ($id == 0) {
            $viewObj->title = 'Nuovo Utente';
            $viewObj->subtitle = "";
            $viewObj->firstLetterInTitle = "";
            $viewObj->backlinkEdit = DOMAIN . "?page=obj&objects=User";
        } else {
            $viewObj->backlinkEdit = DOMAIN . "?page=detail&objects=User" . $this->objectName . "&id=" . $id;
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
}
