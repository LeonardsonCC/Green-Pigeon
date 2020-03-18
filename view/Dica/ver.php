<?php
# Visão view/Dica/ver.php 
/* @var $this DicaController */
/* @var $Dica Dica */
?>
<div class="form-ver container" style="padding: 20px;   ">
    <div class="row">
        <div class="col s12 l12 m12">
            <h3><?php echo $Dica->titulo?></h3>
            <small>Criado em <?php echo $Dica->data_criacao?>, por <?php echo $Dica->getUsuario()->nome?></small>
        </div>
    </div>
    <hr>
    <div class="col s12 l12 m12">
        <?php echo $Dica->texto?>
    </div>
</div>