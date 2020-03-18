<?php
# Visão view/Dica/editar.php
/* @var $this DicaController */
/* @var $Dica Dica */
?>
<div class="Dica editar panel panel-default">
    <div class="panel-heading">
        <h1>Editar Dica</h1>
    </div>
    <div class="panel-body">
    <form class="form-horizontal" method="post" role="form" action="<?php echo $this->Html->getUrl('Dica', 'editar', array($Dica->id)) ?>"  enctype="multipart/form-data">
    <div class="text-info">Os campos marcados com <i class="fa fa-asterisk"></i> são de preenchimento obrigatório.</div>
    <br>
        <?php
            echo $this->Html->getFormHidden('id', $Dica->id);
        # titulo
        if($this->getParam('titulo')){
            echo $this->Html->getFormHidden('titulo', $this->getParam('titulo'));
        } else {
            echo $this->Html->getFormInput('Título', 'titulo', $Dica->titulo, 'text', '', true);
        }
        # texto
        if($this->getParam('texto')){
            echo $this->Html->getFormHidden('texto', $this->getParam('texto'));
        } else {
            echo $this->Html->getFormTextareaHtml('Texto', 'texto', $Dica->texto, '', true);
        }
        # criador_id
        if ($this->getParam('criador_id')) {
            echo $this->Html->getFormHidden('criador_id', $this->getParam('criador_id'));
        } else {
            echo $this->Html->getFormSelect('Usuário criador', 'criador_id', array_columns((array) $Usuarios,'email', 'id'));
        }
        # data_criacao
        if($this->getParam('data_criacao')){
            echo $this->Html->getFormHidden('data_criacao', $this->getParam('data_criacao'));
        } else {
            echo $this->Html->getFormInput('Data de Criação', 'data_criacao', $Dica->data_criacao, 'date', '', true);
        }
        # categoria_id
        if ($this->getParam('categoria_id')) {
            echo $this->Html->getFormHidden('categoria_id', $this->getParam('categoria_id'));
        } else {
            echo $this->Html->getFormSelect('Categoria', 'categoria_id', array_columns((array) $Categorias,'nome', 'id'));
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
            $url_destino = $this->Html->getUrl('Dica', 'lista');
        } ?>
            <a href="<?php echo $url_destino ?>" class="btn btn-default" data-dismiss="modal">Cancelar</a>
            <input type="submit" class="btn btn-primary" value="salvar">
        </div>
    </form>
    </div> <!-- .panel-body -->
</div> <!-- .panel -->
<!-- LazyPHP.com.br -->