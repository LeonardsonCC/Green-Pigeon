<?php
# Visão view/Usuario/ver.php 
/* @var $this UsuarioController */
/* @var $Usuario Usuario */
?>
<div class="container form-ver ">
    <div class="row valign-wrapper">
        <div class="col s12 m12 l4 offset-l1 img-perfil">
            <img class="col materialboxed responsive-img" src="<?php echo SITE_PATH.'/'.$Usuario->avatar ?>" alt="foto de perfil">
        </div>
        <div class="col s12 m6 l6">
            <h3 class="center-align"><?php echo $Usuario->nome; ?></h3>
            <hr>
            <span class="col l6"><b>E-mail: </b></span>
            <span class="col l6"><?php echo $Usuario->email; ?></span>
            <div class="clearfix"></div>
            <hr>
            <span class="col l6"><b>Pontuação: </b></span>
            <span class="col l6"><?php echo $Usuario->pontuacao; ?></span>
            <div class="clearfix"></div>
            <hr>
            <span class="col l6"><b>Pontos de coleta cadastrados: </b></span>
            <span class="col l6"><a href="<?php echo $this->Html->getUrl('Ponto_coleta', 'lista', array($Usuario->id))?>" title="mostrar pontos de coleta"><?php echo $Usuario->getPonto_coletas()->count(); ?></a></span>
            <div class="clearfix"></div>
            <hr>
            <span class="col l6"><b>Dicas cadastradas: </b></span>
            <span class="col l6"><a href="<?php echo $this->Html->getUrl('Dica', 'lista', array('usuario' => $Usuario->id))?>" title="mostrar dicas"><?php echo $Usuario->getDicas()->count(); ?></a></span>
            <div class="clearfix"></div>
            <hr>
            <br>
            <span class="col l6 m12"><?php echo $this->Html->getLink('Editar', 'Usuario', 'editar', array($Usuario->id), array('class' => 'btn blue waves-effect waves-blue', 'style' => 'width: 100%')) ?></span>
            <span class="col l6 m12"><?php echo $this->Html->getModalLink('Apagar conta', 'Usuario', 'apagar', array($Usuario->id), array('class' => 'waves-effect waves-light btn red modal-trigger', 'style' => 'width: 100%')); ?></span>
            <span class="col l6 m12"><?php echo $this->Html->getLink('Logout', 'Login', 'logout', array(), array('class' => 'btn btn-danger hide-on-med-and-up')); ?></span>
            <div class="clearfix"></div>
        </div>
    </div>
</div>