<?php
# Visão view/Usuario/apagar.php 
/* @var $this UsuarioController */
/* @var $Usuario Usuario */
?>
<form class="form" method="post" action="<?php echo $this->Html->getUrl('Usuario', 'apagar') ?>">
    <h4>Confirmação</h4>
    <div>
        <p>Voce tem certeza que deseja excluir o usuário <b><?php echo $Usuario->email; ?></b>?</p>
    </div>
    <div class="right">
        <input type="hidden" name="id" value="<?php echo $Usuario->id; ?>">
        <a href="#" class="btn waves-effect waves-red red modal-close" >Cancelar</a>
        <input type="submit" class="btn waves-effect waves-red green" value="Excluir">
    </div>
    <div class="clearfix"></div>
    <?php
        echo $this->Html->getFormHidden('url_origem', $this->getParam('url_origem'));
    ?>
</form>
<!-- LazyPHP.com.br -->