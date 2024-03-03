<?php
$object = $_GET['object'].'OBJ';
$object_id = $_GET['id'];
$objObj = new $object;
$obj = $objObj->getView($object_id);
$bigSections = $obj->bigSections;

if(isset($_POST['set'])){
    unset($_POST['set']);
    $_POST['id'] = $_GET['id'];
    $messages = $objObj->set($_POST);
    $errors = [];
    if(!isset($messages) || strlen($messages) == 0){ ?>
        <script>window.location.href="<?php echo $obj->backlinkEdit; ?>"</script>
    <?php }
}
?>
<div class="pg-edit-wrapper">
    <div class="pg-edit-header">
        <a href="<?php echo $obj->backlinkEdit; ?>" class="pg-edit-backlink">
            <i class="fa fa-left-long"></i>
        </a>
        <h1>Modifica <?php echo $obj->title ?></h1>
        <h4><?php echo $obj->subtitle ?></h4>
    </div>
    <?php if(isset($messages) && strlen($messages) > 0){
        $title = 'Errore Salvataggio!';
        if(is_array($messages)){
            foreach ($messages as $error) {
                array_push($errors,$error);
            }
        }else{
            array_push($errors,$messages);
        } ?>
        <div class="pg-errors-modal-wrapper">
            <?php require GLOBALPATH.'layouts/partials/modal_errors.php'; ?>
        </div>
    <?php } ?>
    <div class="pg-sections-wrapper">
        <?php foreach($bigSections as $bigSection){ 
            $sections = $bigSection->sections ?>
            <div class="pg-col pg-col-<?php echo $bigSection->col ?> pg-big-section">
                <?php foreach($sections as $section){ 
                    $fields = $section->fields;
                    if($section->visible_edit === true){?>
                        <div class="pg-col pg-col-<?php echo $section->col ?> pg-section">
                            <div class="pg-card">
                                <div class="pg-section-header">
                                    <h4><?php echo $section->title ?></h4>
                                </div>
                                <form action="" class="pg-form-edit" method="POST">
                                    <div class="pg-inputs-wrapper">
                                        <?php foreach($fields as $field){ ?>
                                            <?php if($field->visible_edit == 1){ ?>
                                                <div class="pg-col pg-col-<?php echo $field->col ?> pg-input-wrapper">
                                                    <label for="<?php echo $field->name ?>"><?php echo $field->label ?></label>
                                                    <input type="<?php echo $field->type ?>" name="<?php echo $field->name ?>" value="<?php echo $field->value ?>" class="form-input" id="<?php echo $field->name ?>"></input>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <div class="pg-submit-wrapper">
                                        <button type="submit" class="btn btn-confirm" name="set">Salva</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>