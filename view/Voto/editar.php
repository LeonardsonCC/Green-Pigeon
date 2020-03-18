<?php
# Visão view/Voto/editar.php
/* @var $this VotoController */
/* @var $Voto Voto */
?>
<div class="Voto editar panel panel-default">
    <div class="panel-heading">
        <h1>Editar Voto</h1>
    </div>
    <div class="panel-body">
    <form class="form-horizontal" method="post" role="form" action="<?php echo $this->Html->getUrl('Voto', 'editar', array($Voto->id)) ?>"  enctype="multipart/form-data">
    <div class="text-info">Os campos marcados com <i class="fa fa-asterisk"></i> são de preenchimento obrigatório.</div>
    <br>
        <?php
            echo $this->Html->getFormHidden('id', $Voto->id);
        # usuario_id
        if ($this->getParam('usuario_id')) {
            echo $this->Html->getFormHidden('usuario_id', $this->getParam('usuario_id'));
        } else {
            echo $this->Html->getFormSelect('Usuario', 'usuario_id', array_columns((array) $Usuarios,'email', 'id'));
        }
        # ponto_id
        if ($this->getParam('ponto_id')) {
            echo $this->Html->getFormHidden('ponto_id', $this->getParam('ponto_id'));
        } else {
            echo $this->Html->getFormSelect('Ponto_coleta', 'ponto_id', array_columns((array) $Ponto_coletas,'nome', 'id'));
        }
        # valor
        if($this->getParam('valor')){
            echo $this->Html->getFormHidden('valor', $this->getParam('valor'));
        } else {
            echo $this->Html->getFormInput('Valor', 'valor', $Voto->valor, 'number', '', true);
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
            $url_destino = $this->Html->getUrl('Voto', 'lista');
        } ?>
            <a href="<?php echo $url_destino ?>" class="btn btn-default" data-dismiss="modal">Cancelar</a>
            <input type="submit" class="btn btn-primary" value="salvar">
        </div>
    </form>
    </div> <!-- .panel-body -->
</div> <!-- .panel -->
<!-- LazyPHP.com.br -->