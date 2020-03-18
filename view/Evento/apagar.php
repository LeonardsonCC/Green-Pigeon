<?php
# Visão view/Evento/apagar.php 
/* @var $this EventoController */
/* @var $Evento Evento */
?>
<form class="form" method="post" action="<?php echo $this->Html->getUrl('Evento', 'apagar') ?>">
    <h4>Confirmação</h4>
    <div>
        <p>Voce tem certeza que deseja excluir o evento <b><?php echo $Evento->nome; ?></b>?</p>
    </div>
    <div class="right">
        <input type="hidden" name="id" value="<?php echo $Evento->id; ?>">
        <a href="#" class="btn waves-effect waves-red red modal-close" >Cancelar</a>
        <input type="submit" class="btn waves-effect waves-red green" value="Excluir">
    </div>
    <div class="clearfix"></div>
    <?php
        echo $this->Html->getFormHidden('url_origem', $this->getParam('url_origem'));
    ?>
</form>
<!-- LazyPHP.com.br -->