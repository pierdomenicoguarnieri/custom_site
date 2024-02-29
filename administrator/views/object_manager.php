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
        <table>
            <thead>
                <tr>
                    <?php foreach($listView->fields as $row){
                        if($row->print == 1){ ?>
                            <th>
                                <?php echo $row->label ?>
                            </th>
                        <?php }
                    } ?>
                </tr>
            </thead>
        </table>
        <div class="pg-tbody-container">
            <table>
                <tbody>
                    <?php foreach($datas as $rows){ ?>
                        <tr class="clickable-row" data-href="<?php echo DOMAIN.$listView->singlePage.$rows[0]->value?>">
                            <?php foreach ($rows as $obj) {
                                if($obj->print == 1){ ?>
                                    <td>
                                        <?php echo $obj->value ?>
                                    </td>
                                <?php }
                            } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>