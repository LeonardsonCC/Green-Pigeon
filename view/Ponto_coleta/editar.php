<?php
# Visão view/Ponto_coleta/editar.php
/* @var $this Ponto_coletaController */
/* @var $Ponto_coleta Ponto_coleta */
?>
<div class="Ponto_coleta editar panel panel-default">
    <div class="panel-heading">
        <h1>Editar Ponto_coleta</h1>
    </div>
    <div class="panel-body">
    <form class="form-horizontal" method="post" role="form" action="<?php echo $this->Html->getUrl('Ponto_coleta', 'editar', array($Ponto_coleta->id)) ?>"  enctype="multipart/form-data">
    <div class="text-info">Os campos marcados com <i class="fa fa-asterisk"></i> são de preenchimento obrigatório.</div>
    <br>
        <?php
            echo $this->Html->getFormHidden('id', $Ponto_coleta->id);
        # nome
        if($this->getParam('nome')){
            echo $this->Html->getFormHidden('nome', $this->getParam('nome'));
        } else {
            echo $this->Html->getFormInput('Nome', 'nome', $Ponto_coleta->nome, 'text', '', true);
        }
        # latitude
        if($this->getParam('latitude')){
            echo $this->Html->getFormHidden('latitude', $this->getParam('latitude'));
        } else {
            echo $this->Html->getFormInput('Latitude', 'latitude', $Ponto_coleta->latitude, 'decimal', '', false);
        }
        # longitude
        if($this->getParam('longitude')){
            echo $this->Html->getFormHidden('longitude', $this->getParam('longitude'));
        } else {
            echo $this->Html->getFormInput('Longitude', 'longitude', $Ponto_coleta->longitude, 'decimal', '', false);
        }
        # descricao
        if($this->getParam('descricao')){
            echo $this->Html->getFormHidden('descricao', $this->getParam('descricao'));
        } else {
            echo $this->Html->getFormTextareaHtml('Descricao', 'descricao', $Ponto_coleta->descricao, '', false);
        }
        # exibir
        if($this->getParam('exibir')){
            echo $this->Html->getFormHidden('exibir', $this->getParam('exibir'));
        } else {
            echo $this->Html->getFormInputCheckbox('Exibir', 'exibir', $Ponto_coleta->exibir);
        }
        # aprovacoes
        if($this->getParam('aprovacoes')){
            echo $this->Html->getFormHidden('aprovacoes', $this->getParam('aprovacoes'));
        } else {
            echo $this->Html->getFormInput('Aprovações', 'aprovacoes', $Ponto_coleta->aprovacoes, 'number', '', false);
        }
        # desaprovacoes
        if($this->getParam('desaprovacoes')){
            echo $this->Html->getFormHidden('desaprovacoes', $this->getParam('desaprovacoes'));
        } else {
            echo $this->Html->getFormInput('Desaprovações', 'desaprovacoes', $Ponto_coleta->desaprovacoes, 'number', '', false);
        }
        # data_criacao
        if($this->getParam('data_criacao')){
            echo $this->Html->getFormHidden('data_criacao', $this->getParam('data_criacao'));
        } else {
            echo $this->Html->getFormInput('Data_criacao', 'data_criacao', $Ponto_coleta->data_criacao, 'date', '', false);
        }
        # usuario_id
        if ($this->getParam('usuario_id')) {
            echo $this->Html->getFormHidden('usuario_id', $this->getParam('usuario_id'));
        } else {
            echo $this->Html->getFormSelect('Usuário', 'usuario_id', array_columns((array) $Usuarios,'email', 'id'));
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
            $url_destino = $this->Html->getUrl('Ponto_coleta', 'lista');
        } ?>
            <a href="<?php echo $url_destino ?>" class="btn btn-default" data-dismiss="modal">Cancelar</a>
            <input type="submit" class="btn btn-primary" value="salvar">
        </div>
    </form>
    </div> <!-- .panel-body -->
</div> <!-- .panel -->
<!-- LazyPHP.com.br -->