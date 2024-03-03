<?php
$object = $_GET['object'].'OBJ';
$object_id = $_GET['id'];
$objObj = new $object;
$obj = $objObj->getView($object_id);
$bigSections = $obj->bigSections;
?>

<div class="pg-detail-wrapper">
    <div class="pg-detail-header">
        <a href="<?php echo $obj->backlink; ?>" class="pg-detail-backlink">
            <i class="fa fa-left-long"></i>
        </a>
        <h1><?php echo $obj->title ?></h1>
        <h4><?php echo $obj->subtitle ?></h4>
    </div>
    <div class="pg-sections-wrapper">
        <?php foreach($bigSections as $bigSection){ 
            $sections = $bigSection->sections ?>
            <div class="pg-col pg-col-<?php echo $bigSection->col ?> pg-big-section">
                <?php foreach($sections as $section){ 
                    $fields = $section->fields;
                    if($section->visible === true){?>
                        <div class="pg-col pg-col-<?php echo $section->col ?> pg-section">
                            <div class="pg-card">
                                <div class="pg-section-header">
                                    <h4><?php echo $section->title ?></h4>
                                    <div class="pg-section-buttons-wrapper">
                                        <?php foreach($section->buttons as $button){ ?>
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
                                </div>
                                <?php foreach($fields as $field){ ?>
                                    <?php if($field->visible == 1){ ?>
                                        <div>
                                            <span><?php echo $field->label.": ".$field->value ?></span>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>