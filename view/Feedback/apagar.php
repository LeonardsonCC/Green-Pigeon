<?php
# Visão view/Feedback/apagar.php 
/* @var $this FeedbackController */
/* @var $Feedback Feedback */
?>
<form class="form" method="post" action="<?php echo $this->Html->getUrl('Feedback', 'apagar') ?>">
    <h1>Confirmação</h1>
    <div class="well well-lg">
        <p>Voce tem certeza que deseja excluir o Feedback <strong><?php echo $Feedback->email; ?></strong>?</p>
    </div>
    <div class="text-right">
        <input type="hidden" name="id" value="<?php echo $Feedback->id; ?>">
        <a href="<?php echo $this->Html->getUrl('Feedback', 'lista') ?>" class="btn btn-default" data-dismiss="modal">Cancelar</a>
        <input type="submit" class="btn btn-danger" value="Excluir">
    </div>
    <?php
        echo $this->Html->getFormHidden('url_origem', $this->getParam('url_origem'));
    ?>
</form>
<!-- LazyPHP.com.br -->