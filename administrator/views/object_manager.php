<?php
$objects = explode(",", $_GET["objects"]);
$objectsOBJ = array();
for ($i = 0; $i < count($objects); $i++) {
    $n = $objects[$i] . "OBJ";
    $objectsOBJ[strtoupper($objects[$i])] = new $n;
}
$filter = false;
$where = array();
$fields = [];
foreach($_POST as $key => $input){
    if($key == 'fields'){
        $fields = json_decode(base64_decode($_POST['fields']));
    }

    if(strpos($key,"select_") !== false){
        switch ($input) {
            case '1':
                $condition = "LIKE";
                break;
            case '2':
                $condition = ">";
                break;
            case '3':
                $condition = ">=";
                break;
            case '4':
                $condition = "<";
                break;
            case '5':
                $condition = "<=";
                break;
            case '6':
                $condition = "=";
                break;
            default:
                $condition = "=";
                break;
        }
    }

    if(strpos($key,"input_") !== false && strlen($input) > 0){
        $name = str_replace("input_","",$key);
        foreach ($fields as $field) {
            if($field->name == $name){
                if($field->type_column == 'flag'){
                    $input = strtolower($input) == 'si' ? 1 : 0 ;
                }
            }
        }
        if($condition == "LIKE"){
            $input = "%".$input."%";
        }
        array_push($where,"UPPER($name) $condition UPPER('$input')");
    }
}

if(count($where) > 0){
    $filter = true;
}
?>

<div class="pg-object-contaier">
    <?php foreach($objectsOBJ as $object){
        $listView = $object->getListView($GLOBALS['CURRENT_USER']->id);
        if($filter){
            $query = "SELECT * FROM ($listView->query) AS t123";
            if(count($where) > 0){
                $query .= " WHERE ";
                foreach($where as $key => $condition){
                    if(is_array($condition)){
                        $query .= " (";
                        foreach ($condition as $key_2 => $object) {
                            if(is_object($object)){
                                $is_last = $key_2 == count($condition) - 1 ? ")" : "";
                                $query .= " (".$object->string.")  $is_last $object->condition ";
                            }
                        }
                    }elseif(is_object($condition)){
                        $object_condition = isset($condition->condition) ? $condition->condition : "";
                        $query .= " $condition->string $object_condition ";
                    }else{
                        $separator = $key == count($where) - 1 ? "" : "AND";
                        $query .= " $condition $separator ";
                    }
                }
            }
            $datas = $object->getData($query,$listView->table,$listView->fields);
        }else{
            $datas = $listView->datas;
        }?>
        <div class="pg-buttons-wrapper">
            <?php foreach($listView->buttons as $button){ ?>
                <div class="pg-button-wrapper">                                            
                    <a href="<?php echo $button->link ?>">
                        <button class="btn<?php echo isset($button->button_class) ? ' btn-'.$button->button_class : '' ?>">
                            <?php if(isset($button->icon)){ ?>
                                <i class="fa fa-<?php echo $button->icon ?>"></i>
                            <?php } ?>
                            <span><?php echo $button->label ?></span>
                        </button>
                    </a>
                </div>
            <?php } ?>
        </div>
        <div class="pg-table">
            <div class="pg-table-head">
                <div class="pg-table-row">
                    <form action="" method="POST">
                        <input type="text" value="<?php echo base64_encode(json_encode($listView->fields)) ?>" name="fields" class="pg-input-fields">
                        <?php foreach($listView->fields as $row){
                            if($row->print == 1){ ?>
                                <div class="pg-th pg-col pg-col-<?php echo $row->col ?>">
                                    <button class="btn btn-xsmall btn-confirm pg-filter-button" type="button">
                                        <i class="fa-solid fa-filter"></i>
                                    </button>
                                    <?php echo $row->label ?>
                                    <div class="pg-input-wrapper <?php echo $filter && !empty($_POST["input_".$row->name]) ? '' : 'hided'; ?>">
                                        <div class="pg-filters-container">
                                            <button class="btn btn-small pg-toggle-filter" type="button">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <select name="select_<?php echo $row->name ?>" class="pg-filters-select hidden">
                                                <option value="1">Contiene</option>
                                                <option value="2">Maggiore a</option>
                                                <option value="3">Maggiore uguale a</option>
                                                <option value="4">Minore a</option>
                                                <option value="5">Minore uguale a</option>
                                                <option value="6">Uguale a</option>
                                            </select>
                                        </div>
                                        <input class="form-input hided" type="<?php echo $row->type ?>" name="input_<?php echo $row->name ?>" value="<?php echo $filter && strlen($_POST["input_".$row->name]) > 0 ? $_POST["input_".$row->name] : ''?>">
                                    </div>
                                </div>
                            <?php }
                        } ?>
                        <button class="btn btn-small pg-search-button" type="submit" title="Cerca">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="pg-tbody-container">
                <div class="pg-tbody">
                    <?php if((is_array($datas) && count($datas) > 0) || $datas != NULL) {                        
                    foreach($datas as $rows){ ?>
                        <div class="pg-tr clickable-row" data-href="<?php echo DOMAIN.$listView->singlePage.$rows[0]->value?>">
                            <?php foreach ($rows as $obj) {
                                if($obj->print == 1){ ?>
                                    <div class="pg-td pg-col pg-col-<?php echo $obj->col ?>">
                                        <?php echo $obj->value ?>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    <?php }
                    }else{ ?>
                        <div class="pg-tr">
                            <div class="pg-td pg-col pg-no-data">
                                <span>Nessun dato trovato</span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>