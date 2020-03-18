<?php
# Visão view/Categoria/apagar.php 
/* @var $this CategoriaController */
/* @var $Categoria Categoria */
?>
<form class="form" method="post" action="<?php echo $this->Html->getUrl('Categoria', 'apagar') ?>">
    <h1>Confirmação</h1>
    <div class="well well-lg">
        <p>Voce tem certeza que deseja excluir o Categoria <strong><?php echo $Categoria->nome; ?></strong>?</p>
    </div>
    <div class="text-right">
        <input type="hidden" name="id" value="<?php echo $Categoria->id; ?>">
        <a href="<?php echo $this->Html->getUrl('Categoria', 'lista') ?>" class="btn btn-default" data-dismiss="modal">Cancelar</a>
        <input type="submit" class="btn btn-danger" value="Excluir">
    </div>
    <?php
        echo $this->Html->getFormHidden('url_origem', $this->getParam('url_origem'));
    ?>
</form>
<!-- LazyPHP.com.br -->