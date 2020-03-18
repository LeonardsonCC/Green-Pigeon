<?php
# Visão view/Feedback/cadastrar.php
/* @var $this FeedbackController */
/* @var $Feedback Feedback */
?>
<div>
    <h2>Deixe o seu feedback!</h2>
    <form action="<?php echo $this->Html->getUrl('Feedback', 'cadastrar'); ?>" method="post">
        <?php
        echo $this->Html->getFormTextareaHtml('Conte sua experiência no site', 'texto', '', 'Fale sobre possíveis erros encontrados ou coisas que gostou, qualquer coisa...', true);
        ?>
        <div class="clearfix"></div>
        <hr>
        <?php
        echo $this->Html->getFormInputBlackText('Digite seu e-mail (opcional)', 'email', '', 'text', '', false);
        ?>
        <p class="center">
            <label>
                <input type="checkbox" name="receber_email" />
                <span class="black-text">Você gostaria de ser informado se este projeto se tornasse real?</span>
            </label>
        </p>
        <div class="right">
            <a href="#" class="btn waves-effect waves-red red modal-close" data-dismiss="modal">Cancelar</a>
            <input type="submit" class="btn waves-effect waves-green green" value="Enviar">
        </div>
    </form>
</div>