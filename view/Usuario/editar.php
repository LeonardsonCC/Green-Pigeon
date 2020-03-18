<?php
# Visão view/Usuario/editar.php
/* @var $this UsuarioController */
/* @var $Usuario Usuario */
?>
<div class="Dica container" style="margin-top: 10vh">
    <div class="row">
        <div class="col s12 m6 l6 offset-m3 offset-l3 form-cadastrar">
            <h3>Editar usuário</h3>
            <div class="">
                <form method="post" action="<?php echo $this->Html->getUrl('Usuario', 'editar', array($Usuario->id)) ?>"  enctype="multipart/form-data">
                    <?php
                    echo $this->Html->getFormHidden('id', $Usuario->id);
                    # nome
                    if ($this->getParam('nome')) {
                        echo $this->Html->getFormHidden('nome', $this->getParam('nome'));
                    } else {
                        echo $this->Html->getFormInput('Nome', 'nome', $Usuario->nome, 'text', '', false);
                    }
                    # email
                    if ($this->getParam('email')) {
                        echo $this->Html->getFormHidden('email', $this->getParam('email'));
                    } else {
                        echo $this->Html->getFormInput('Email', 'email', $Usuario->email, 'email', '', true);
                    }
                    # senha
                    if ($this->getParam('senha')) {
                        echo $this->Html->getFormHidden('senha', $this->getParam('senha'));
                    } else {
                        echo $this->Html->getFormInput('Senha', 'senha', '', 'password', '', false);
                    }
                    # avatar
                    if ($this->getParam('avatar')) {
                        echo $this->Html->getFormHidden('avatar', $this->getParam('avatar'));
                    } else {
                        echo $this->Html->getFormInput('Avatar', 'avatar', $Usuario->avatar, 'file', '', false);
                    }
                    # pontuacao
                    if($Usuario->adm == 2){
                        echo $this->Html->getFormInput('Pontuação', 'pontuacao', $Usuario->pontuacao, 'number', '', false);
                    } else {
                        echo $this->Html->getFormHidden('pontuacao', $Usuario->pontuacao);
                    }

                    if ($this->getParam('url_origem')) {
                        echo $this->Html->getFormHidden('url_origem', $this->getParam('url_origem'));
                    }
                    ?>
                    <div class="clearfix"></div>
                    <div class = "form-enviar right">
                        <?php
                        if ($this->getParam('url_origem')) {
                            $url_destino = Cript::decript($this->getParam('url_origem'));
                        } else {
                            $url_destino = $this->Html->getUrl('Usuario', 'lista');
                        }
                        ?>
                        <a href="<?php echo $url_destino ?>" class="btn-flat white-text waves-effect waves-red" data-dismiss="modal">Cancelar</a>
                        <button type="submit" class="btn green" value="">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
zzzz