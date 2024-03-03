<style>
#modal-error{
    display: flex;
    padding: 10px;
    border: 2px solid black;
    width: 30%;
    align-items: center;
    border-radius: 5px;
    flex-direction: column;
    margin-bottom: 20px;
    background-color: white;
    h3{
        margin-bottom: 20px;
    }
    span{
        color: red;
        margin-bottom: 20px;
    }
    button{
        color: white;
    }
}
</style>

<div id="modal-error">
    <h3><?php echo $title ?></h3>
    <?php foreach($errors as $error){ ?>
        <span><?php echo $error ?></span>
    <?php } ?>
    <button class="btn btn-confirm" type="button" onclick="hideModal()">OK</button>
</div>

<script>
    function hideModal(){
        modal = document.getElementById('modal-error')
        modal.style.display = 'none';
    }
</script>