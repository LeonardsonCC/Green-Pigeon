<?php
# Visão view/Evento/editar.php
/* @var $this EventoController */
/* @var $Evento Evento */
?>
<div class="Evento editar panel panel-default">
    <div class="panel-heading">
        <h1>Editar Evento</h1>
    </div>
    <div class="panel-body">
    <form class="form-horizontal" method="post" role="form" action="<?php echo $this->Html->getUrl('Evento', 'editar', array($Evento->id)) ?>"  enctype="multipart/form-data">
    <div class="text-info">Os campos marcados com <i class="fa fa-asterisk"></i> são de preenchimento obrigatório.</div>
    <br>
        <?php
            echo $this->Html->getFormHidden('id', $Evento->id);
        # titulo
        if($this->getParam('titulo')){
            echo $this->Html->getFormHidden('titulo', $this->getParam('titulo'));
        } else {
            echo $this->Html->getFormInput('Titulo', 'titulo', $Evento->titulo, 'text', '', true);
        }
        # texto
        if($this->getParam('texto')){
            echo $this->Html->getFormHidden('texto', $this->getParam('texto'));
        } else {
            echo $this->Html->getFormTextareaHtml('Texto', 'texto', $Evento->texto, '', true);
        }
        # data_criacao
        if($this->getParam('data_criacao')){
            echo $this->Html->getFormHidden('data_criacao', $this->getParam('data_criacao'));
        } else {
            echo $this->Html->getFormInput('Data_criacao', 'data_criacao', $Evento->data_criacao, 'date', '', false);
        }
        # latitude
        if($this->getParam('latitude')){
            echo $this->Html->getFormHidden('latitude', $this->getParam('latitude'));
        } else {
            echo $this->Html->getFormInput('Latitude', 'latitude', $Evento->latitude, 'text', '', false);
        }
        # longitude
        if($this->getParam('longitude')){
            echo $this->Html->getFormHidden('longitude', $this->getParam('longitude'));
        } else {
            echo $this->Html->getFormInput('Longitude', 'longitude', $Evento->longitude, 'text', '', false);
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
            $url_destino = $this->Html->getUrl('Evento', 'lista');
        } ?>
            <a href="<?php echo $url_destino ?>" class="btn btn-default" data-dismiss="modal">Cancelar</a>
            <input type="submit" class="btn btn-primary" value="salvar">
        </div>
    </form>
    </div> <!-- .panel-body -->
</div> <!-- .panel -->
<!-- LazyPHP.com.br -->