<?php
# Visão view/Feedback/editar.php
/* @var $this FeedbackController */
/* @var $Feedback Feedback */
?>
<div class="Feedback editar panel panel-default">
    <div class="panel-heading">
        <h1>Editar Feedback</h1>
    </div>
    <div class="panel-body">
    <form class="form-horizontal" method="post" role="form" action="<?php echo $this->Html->getUrl('Feedback', 'editar', array($Feedback->id)) ?>"  enctype="multipart/form-data">
    <div class="text-info">Os campos marcados com <i class="fa fa-asterisk"></i> são de preenchimento obrigatório.</div>
    <br>
        <?php
            echo $this->Html->getFormHidden('id', $Feedback->id);
        # texto
        if($this->getParam('texto')){
            echo $this->Html->getFormHidden('texto', $this->getParam('texto'));
        } else {
            echo $this->Html->getFormTextareaHtml('Texto', 'texto', $Feedback->texto, '', false);
        }
        # email
        if($this->getParam('email')){
            echo $this->Html->getFormHidden('email', $this->getParam('email'));
        } else {
            echo $this->Html->getFormInput('Email', 'email', $Feedback->email, 'text', '', false);
        }
        # receber_email
        if($this->getParam('receber_email')){
            echo $this->Html->getFormHidden('receber_email', $this->getParam('receber_email'));
        } else {
            echo $this->Html->getFormTextareaHtml('Receber_email', 'receber_email', $Feedback->receber_email, '', true);
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
            $url_destino = $this->Html->getUrl('Feedback', 'lista');
        } ?>
            <a href="<?php echo $url_destino ?>" class="btn btn-default" data-dismiss="modal">Cancelar</a>
            <input type="submit" class="btn btn-primary" value="salvar">
        </div>
    </form>
    </div> <!-- .panel-body -->
</div> <!-- .panel -->
<!-- LazyPHP.com.br -->