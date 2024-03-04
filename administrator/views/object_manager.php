<?php
$objects = explode(",", $_GET["objects"]);
$objectsOBJ = array();
for ($i = 0; $i < count($objects); $i++) {
    $n = $objects[$i] . "OBJ";
    $objectsOBJ[strtoupper($objects[$i])] = new $n;
}
?>

<div class="pg-object-contaier">
    <?php foreach($objectsOBJ as $object){
        $listView = $object->getListView($GLOBALS['CURRENT_USER']->id);
        $datas = $object->getListView($GLOBALS['CURRENT_USER']->id)->datas;?>
        <div class="pg-table">
            <div class="pg-table-head">
                <div class="pg-table-row">
                    <?php foreach($listView->fields as $row){
                        if($row->print == 1){ ?>
                            <div class="pg-th pg-col pg-col-<?php echo $row->col ?>">
                                <button class="btn btn-xsmall btn-confirm pg-filter-button">
                                    <i class="fa-solid fa-filter"></i>
                                </button>
                                <?php echo $row->label ?>
                                <div class="pg-input-wrapper hided">
                                    <div class="pg-filters-container">
                                        <button class="btn btn-small">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <div class="pg-filters-select">
                                            <span>aaa</span>
                                            <span>aaa</span>
                                            <span>aaa</span>
                                            <span>aaa</span>
                                            <span>aaa</span>
                                            <span>aaa</span>
                                        </div>
                                    </div>
                                    <input class="form-input hided" type="<?php echo $row->type ?>">
                                </div>
                            </div>
                        <?php }
                    } ?>
                </div>
            </div>
            <div class="pg-tbody-container">
                <div class="pg-tbody">
                    <?php foreach($datas as $rows){ ?>
                        <div class="pg-tr clickable-row" data-href="<?php echo DOMAIN.$listView->singlePage.$rows[0]->value?>">
                            <?php foreach ($rows as $obj) {
                                if($obj->print == 1){ ?>
                                    <div class="pg-td pg-col pg-col-<?php echo $obj->col ?>">
                                        <?php echo $obj->value ?>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>