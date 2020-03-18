<?php
# Visão view/Usuario/cadastrar.php
/* @var $this UsuarioController */
/* @var $Usuario Usuario */
?>
<div class="Usuario cadastrar panel panel-default">
    <div class="panel-heading">
        <h1>Cadastrar Usuario</h1>
    </div>
    <div class="panel-body">
    <form class="form-horizontal" method="post" role="form" action="<?php echo $this->Html->getUrl('Usuario', 'cadastrar') ?>"  enctype="multipart/form-data">
    <div class="text-info">Os campos marcados com <i class="fa fa-asterisk"></i> são de preenchimento obrigatório.</div>
    <br>
        <?php
        # email
        echo $this->Html->getFormInput('Email', 'email', $Usuario->email, 'email', '', true);
        # senha
        echo $this->Html->getFormInput('Senha', 'senha', $Usuario->senha, 'password', '', true);
        # nome
            echo $this->Html->getFormInput('Nome', 'nome', $Usuario->nome, 'text', '', false);
        # avatar
            echo $this->Html->getFormInput('Avatar', 'avatar', $Usuario->avatar, 'text', '', false);
        # adm
        echo $this->Html->getFormInputCheckbox('Adm', 'adm', $Usuario->adm);
        # pontuacao
        echo $this->Html->getFormInput('Pontuação', 'pontuacao', $Usuario->pontuacao, 'number', '', false);
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
            $url_destino = $this->Html->getUrl('Usuario', 'lista');
        } ?>
            <a href="<?php echo $url_destino ?>" class="btn btn-default" data-dismiss="modal">Cancelar</a>
            <input type="submit" class="btn btn-primary" value="salvar">
        </div>
    </form>
    </div> <!-- .panel-body -->
</div> <!-- .panel -->
<!-- LazyPHP.com.br -->