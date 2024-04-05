<?php
class PatientOBJ extends DBObject
{
    public $objectName = "Patient";
    public $icon = "patient";
    public $label = "Pazienti";
    public $filterColor = "orange";
    public $filterColumn = "name";
    public $filterExernal = "email";


    public function __construct(){
        parent::__construct('patients');
        $this->noShowFields = array();
        $this->TagLogName = "Patient";
    }

    public function getListView($currentLoggedUser){
        $viewObj = new stdClass();
        $viewObj->singlePage = "?page=detail&object=" . $this->objectName . "&id=";
        $viewObj->titlePage = "Pazienti";
        $viewObj->hasAdd = true;
        $viewObj->linkAdd = ADMINPATH . "?page=edit&object=" . $this->objectName;
        $viewObj->buttons = array(
            (object)["label" => "Aggiungi", "icon" => "plus", "button_class" => "confirm btn-small", "link" => DOMAIN."?page=edit&object=".$this->objectName."&id="]
        );
        $viewObj->fields = array(
            (object)['name' => "id", "label" => "ID", "col" => "0", "type" => "text", "type_column" => "int", "print" => 0],
            (object)['name' => "name", "label" => "Nome", "col" => "4", "type" => "text", "type_column" => "varchar", "print" => 1],
            (object)['name' => "surname", "label" => "Cognome", "col" => "4", "type" => "text", "type_column" => "varchar", "print" => 1],
            (object)['name' => "email", "label" => "Email", "col" => "4", "type" => "text", "type_column" => "varchar", "print" => 1],
        );
        $replace = ["email" => "(SELECT email FROM users WHERE users.id_patient = patients.id) AS email"];
        $viewObj->datas = $this->getData("",$this->table,$viewObj->fields,$replace);
        $viewObj->table = $this->table;
        return $viewObj;
    }

    public function set($params, $returnError = false, $blockInsert = false){
        $message = '';
        if (strlen($params['name']) && strlen($params['surname'])) {
            $message = $this->popolateArray($params, $this->objectName);
        } else {
            if (!strlen($params['name'])) {
                $message = 'Il nome è obbligatorio!';
            }
            if (!strlen($params['surname'])) {
                $message = 'Il cognome è obbligatorio!';
            }
        }
        return $message;
    }

    public function getView($id = 0, $currentLoggedUser = null){
        $this->select($id)->load();
        $obj = $this->get();

        $viewObj = new stdClass();
        $viewObj->title = $this->getProperty($obj, "name") . " " . $this->getProperty($obj, "surname");
        $viewObj->subtitle = $this->getProperty($obj, "email");
        $viewObj->backlink = DOMAIN . "?page=obj&objects=". $this->objectName;
        if ($id == 0) {
            $viewObj->title = 'Nuovo Paziente';
            $viewObj->subtitle = "";
            $viewObj->firstLetterInTitle = "";
            $viewObj->backlinkEdit = DOMAIN . "?page=obj&objects=". $this->objectName;
        } else {
            $viewObj->backlinkEdit = DOMAIN . "?page=detail&object=" . $this->objectName . "&id=" . $id;
        }

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
        );
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
}
