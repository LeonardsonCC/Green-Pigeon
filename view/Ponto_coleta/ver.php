<?php
# Visão view/Ponto_coleta/ver.php 
/* @var $this Ponto_coletaController */
/* @var $Ponto_coleta Ponto_coleta */
?>
<div class="ver ponto_coleta panel panel-default">
<div class="panel-body">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h1><?php echo $Ponto_coleta->nome;?></h1>
        </div>
        <div class="panel-body">
            <div class="atributo latitude col-sm-6">
                <div class="name col-md-3"><strong>Latitude</strong>: </div>
                <div class="value col-md-9"><?php echo $Ponto_coleta->latitude;?></div>
            </div>
            <div class="atributo longitude col-sm-6">
                <div class="name col-md-3"><strong>Longitude</strong>: </div>
                <div class="value col-md-9"><?php echo $Ponto_coleta->longitude;?></div>
            </div>
            <div class="atributo descricao col-sm-12">
                <div class="name"><strong>Descricao</strong>: </div>
                <div class="value"><?php echo $Ponto_coleta->descricao;?></div>
            </div>
            <div class="atributo exibir col-sm-6">
                <div class="name col-md-3"><strong>Exibir</strong>: </div>
                <?php
                $check = '<i class="fa fa-times"></i>';
                if($Ponto_coleta->exibir){
                    $check = '<i class="fa fa-check"></i>';
                }
                ?>
                <div class="value col-md-9"><?php echo $check;?></div>
            </div>
            <div class="atributo aprovacoes col-sm-6">
                <div class="name col-md-3"><strong>Aprovações</strong>: </div>
                <div class="value col-md-9"><?php echo $Ponto_coleta->aprovacoes;?></div>
            </div>
            <div class="atributo desaprovacoes col-sm-6">
                <div class="name col-md-3"><strong>Desaprovações</strong>: </div>
                <div class="value col-md-9"><?php echo $Ponto_coleta->desaprovacoes;?></div>
            </div>
            <div class="atributo data_criacao col-sm-6">
                <div class="name col-md-3"><strong>Data_criacao</strong>: </div>
                <?php $data = new DateTime($Ponto_coleta->data_criacao); ?>
                <div class="value col-md-9"><?php echo date_format($data, 'd/m/Y');?></div>
            </div>
            <div class="atributo col-sm-6">
                <div class="col-md-3"><strong>Usuario</strong>: </div>
                <div class="col-md-9">
                    <?php
                    echo $this->Html->getLink($Ponto_coleta->getUsuario()->email, 'Usuario', 'ver',
                        array($Ponto_coleta->getUsuario()->id), // variaveis via GET opcionais
                        array('class' => '')); // atributos HTML opcionais
                ?>
                </div>
            </div>
            <div class="atributo col-sm-6">
                <div class="col-md-3"><strong>Categoria</strong>: </div>
                <div class="col-md-9">
                    <?php
                    echo $this->Html->getLink($Ponto_coleta->getCategoria()->nome, 'Categoria', 'ver',
                        array($Ponto_coleta->getCategoria()->id), // variaveis via GET opcionais
                        array('class' => '')); // atributos HTML opcionais
                ?>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <br>
    

    
</div>
</div>
<!-- LazyPHP.com.br -->