<?php
$Ponto_coleta = new Ponto_coleta($this->getParam(0));
?>
<script>
    $(document).ready(function () {
        $('select').formSelect();
    });
</script>
<style>
    .input-field label{
        color: black !important;
    }
    .select-dropdown{
        color: black !important;
    }
</style>    

<h3>Votar para <?php echo $Ponto_coleta->nome; ?></h3>
<p>Votando você colabora com a comunidade, informando se este ponto é verídico!</p>
<hr><br>
<form method="post" action="<?php echo $this->Html->getUrl('Voto', 'votar') ?>">
    <div class="input-field">
        <select name="valor" class="black-text">
            <option value="" disabled selected>Escolha uma opção</option>
            <option class="" value="1">Aprovar</option>
            <option class="" value="0">Desaprovar</option>
        </select>
        <label class="black-text">Selecione uma opção:</label>
    </div>
    <input type="hidden" name="ponto_id" value="<?php echo $Ponto_coleta->id ?>">
    <div class="right">
        <a href="#" class="btn waves-effect waves-red red modal-close">Cancelar</a>
        <input class="btn waves-effect waves-light right green" type="submit" value="Votar">
    </div>
    <div class="clearfix"></div>
</form>