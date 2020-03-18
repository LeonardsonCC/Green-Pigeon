<?php
# Visão view/Dica/apagar.php 
/* @var $this DicaController */
/* @var $Dica Dica */
?>
<form class="form" method="post" action="<?php echo $this->Html->getUrl('Dica', 'apagar') ?>">
    <h4>Confirmação</h4>
    <div>
        <p>Voce tem certeza que deseja excluir o Dica <b><?php echo $Dica->titulo; ?></b>?</p>
    </div>
    <div class="right">
        <input type="hidden" name="id" value="<?php echo $Dica->id; ?>">
        <a href="#" class="btn waves-effect waves-red red modal-close" data-dismiss="modal">Cancelar</a>
        <input type="submit" class="btn waves-effect waves-red green" value="Excluir">
    </div>
    <?php
        echo $this->Html->getFormHidden('url_origem', $this->getParam('url_origem'));
    ?>
</form>
<!-- LazyPHP.com.br -->