<?php
# Visão view/Voto/ver.php 
/* @var $this VotoController */
/* @var $Voto Voto */
?>
<div class="ver voto panel panel-default">
<div class="panel-body">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h1><?php echo $Voto->usuario_id;?></h1>
        </div>
        <div class="panel-body">
            <div class="atributo col-sm-6">
                <div class="col-md-3"><strong>Ponto_coleta</strong>: </div>
                <div class="col-md-9">
                    <?php
                    echo $this->Html->getLink($Voto->getPonto_coleta()->nome, 'Ponto_coleta', 'ver',
                        array($Voto->getPonto_coleta()->id), // variaveis via GET opcionais
                        array('class' => '')); // atributos HTML opcionais
                ?>
                </div>
            </div>
            <div class="atributo valor col-sm-6">
                <div class="name col-md-3"><strong>Valor</strong>: </div>
                <div class="value col-md-9"><?php echo $Voto->valor;?></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <br>
    

    
</div>
</div>
<!-- LazyPHP.com.br -->