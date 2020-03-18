<?php
# Visão view/Voto/apagar.php 
/* @var $this VotoController */
/* @var $Voto Voto */
?>
<form class="form" method="post" action="<?php echo $this->Html->getUrl('Voto', 'apagar') ?>">
    <h1>Confirmação</h1>
    <div class="well well-lg">
        <p>Voce tem certeza que deseja excluir o Voto <strong><?php echo $Voto->id; ?></strong>?</p>
    </div>
    <div class="text-right">
        <input type="hidden" name="id" value="<?php echo $Voto->id; ?>">
        <a href="<?php echo $this->Html->getUrl('Voto', 'lista') ?>" class="btn btn-default" data-dismiss="modal">Cancelar</a>
        <input type="submit" class="btn btn-danger" value="Excluir">
    </div>
    <?php
        echo $this->Html->getFormHidden('url_origem', $this->getParam('url_origem'));
    ?>
</form>
<!-- LazyPHP.com.br -->