<?php
# Visão view/Categoria/editar.php
/* @var $this CategoriaController */
/* @var $Categoria Categoria */
?>
<div class="Categoria editar panel panel-default">
    <div class="panel-heading">
        <h1>Editar Categoria</h1>
    </div>
    <div class="panel-body">
    <form class="form-horizontal" method="post" role="form" action="<?php echo $this->Html->getUrl('Categoria', 'editar', array($Categoria->id)) ?>"  enctype="multipart/form-data">
    <div class="text-info">Os campos marcados com <i class="fa fa-asterisk"></i> são de preenchimento obrigatório.</div>
    <br>
        <?php
            echo $this->Html->getFormHidden('id', $Categoria->id);
        # nome
        if($this->getParam('nome')){
            echo $this->Html->getFormHidden('nome', $this->getParam('nome'));
        } else {
            echo $this->Html->getFormInput('Nome', 'nome', $Categoria->nome, 'text', '', true);
        }
        # link
        if($this->getParam('link')){
            echo $this->Html->getFormHidden('link', $this->getParam('link'));
        } else {
            echo $this->Html->getFormInput('Link', 'link', $Categoria->link, 'text', '', true);
        }
        if($this->getParam('url_origem')){
            echo $this->Html->getFormHidden('url_origem', $this->getParam('url_origem'));
        }
        ?>
        <div class="clearfix"></div>
        <div class="text-right">
        <?php
        if($this->getParam('url_origem')){
            $url_destino = Cript::decript($this->getParam('url_origem'));
        } else {
            $url_destino = $this->Html->getUrl('Categoria', 'lista');
        } ?>
            <a href="<?php echo $url_destino ?>" class="btn btn-default" data-dismiss="modal">Cancelar</a>
            <input type="submit" class="btn btn-primary" value="salvar">
        </div>
    </form>
    </div> <!-- .panel-body -->
</div> <!-- .panel -->
<!-- LazyPHP.com.br -->