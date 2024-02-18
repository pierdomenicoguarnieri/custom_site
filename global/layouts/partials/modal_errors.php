<style>
#modal-error{
    display: flex;
    border: 2px solid grey;
    width: 30%;
    align-items: center;
    border-radius: 5px;
    flex-direction: column;
    margin-bottom: 20px;
    span{
        color: red;
        margin-bottom: 20px;
    }
    #confirm-modal{
        margin-bottom: 20px;
        background: green;
        border: 1px solid transparent;
        border-radius: 5px;
        cursor: pointer;
        font-size: 15px;
        padding: 5px 10px;
        transition: all .3s;
        color: white;
    }
    #confirm-modal:hover {
        background-color: darkgreen !important;
        transition: all 0.3s;
    }
}
</style>

<div id="modal-error">
    <h3><?php echo $title ?></h3>
    <?php foreach($errors as $error){ ?>
        <span><?php echo $error ?></span>
    <?php } ?>
    <button id="confirm-modal" type="button" onclick="hideModal()">OK</button>
</div>

<script>
    function hideModal(){
        modal = document.getElementById('modal-error')
        modal.style.display = 'none';
    }
</script>