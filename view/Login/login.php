<div class="container" style="margin-bottom: 70px;">
    <div class="row">
        <!-- Titulo -->
        <h1 class="col s12 m12 l12 xl12 center-align white-text titulo">Entre com sua conta</h1>
    </div>
    <div class="row">
        <div class="col s12 m12 l6 xl6 white-text" id="entrar-inicio">
            <div class="center-align">
                <h3>Registrar-se</h3>
                <form action="<?php echo $this->Html->getUrl('Usuario', 'cadastrar') ?>" method="post" id="registrar-form">
                    <div class="input-field col s12 l12 xl12 white-text">
                        <input id="nome" name="nome" type="text" class="white-text">
                        <label for="nome">Seu nome</label>
                    </div>
                    <div class="input-field col s12 l12 xl12">
                        <input id="remail" name="email" type="text" class="validate white-text">
                        <label for="remail">E-mail</label>
                    </div>
                    <div class="input-field col s12 l12 xl12">
                        <input id="rsenha" name="senha" type="password" class="white-text">
                        <label for="rsenha">Senha</label>
                    </div>
                    <div class="input-field col s12 l12 xl12">
                        <input id="csenha" name="csenha" type="password" class="white-text">
                        <label for="csenha">Confirme sua senha</label>
                    </div>
                    <p>
                        <label>
                            <input id="termos" type="checkbox" />
                            <span class="white-text">Eu aceito os termos de uso.</span>
                        </label>
                    </p>
                    <button class="waves-effect waves-light btn-large  green darken-2" type="submit" id="regForm">Registrar-se</button>
                </form>

            </div>
        </div>
        <div class="col s1 m1 l1 xl1"></div>
        <div class="col s12 m12 l5 xl5 white-text" id="entrar-inicio">
            <!-- Login -->
            <div class="center-align" id="">
                <h3>Entrar</h3>
                <form action="" method="post" id="formulario-entrar">
                    <div class="input-field col s12 l12 xl12">
                        <input id="login" name="login" type="text" class="white-text">
                        <label for="login">E-mail</label>
                    </div>
                    <div class="input-field col s12 l12 xl12">
                        <input id="password" class="white-text" name="password" type="password">
                        <label for="password">Senha</label>
                    </div>
                    <p>
                        <?php echo $this->Html->getLink('Esqueceu sua senha?', 'Login', 'send'); ?>
                    </p>
                    <button class="waves-effect waves-light btn-large green darken-2 " href="#mapa">Entrar</button>
                </form>

            </div>

        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        
        $('#registrar-form').submit( function(e) {
            if( !formCheckRegistrar() ){
                e.preventDefault();
                M.toast({html: 'Verifique os campos para cadastro!', classes: 'red white-text'});
            }
        });
        
        $('#formulario-entrar').submit( function(e) {
            if( !formCheckEntrar() ){
                e.preventDefault();
                M.toast({html: 'Verifique os campos para login!', classes: 'red white-text'});
            }
        });

        function formCheckRegistrar() {
            var prontoNome = false;
            var prontoEmail = false;
            var prontoSenha = false;
            var prontoTermos = false;

            if ($('#nome').val() !== '') {
                $('#nome').addClass('valid');
                $('#nome').removeClass('invalid');
                prontoNome = true;
            } else {
                prontoNome = false;
                $('#nome').addClass('invalid');
                $('#nome').removeClass('valid');
            }
            if ($('#remail').val() !== '') {
                $('#remail').addClass('valid');
                $('#remail').removeClass('invalid');
                prontoEmail = true;
            } else {
                prontoEmail = false;
                $('#remail').addClass('invalid');
                $('#remail').removeClass('valid');
            }
            if ($('#rsenha').val() != $('#csenha').val()) {
                $('#csenha').addClass('invalid');
                $('#csenha').removeClass('valid');
                prontoSenha = false;
            } else {
                $('#csenha').addClass('valid');
                $('#csenha').removeClass('invalid');
                prontoSenha = true;
            }

            if ($('#termos').is(':checked')) {
                prontoTermos = true;
            } else {
                prontoTermos = false;
            }
            
            if (prontoNome && prontoEmail && prontoSenha && prontoTermos) {
                return true;
            } else{
                return false;
            }
        }
        
        function formCheckEntrar() {
            var prontoEmail = false;
            var prontoSenha = false;
            
            if ($('#login').val() !== '') {
                $('#login').addClass('valid');
                $('#login').removeClass('invalid');
                prontoEmail = true;
            } else {
                prontoEmail = false;
                $('#login').addClass('invalid');
                $('#login').removeClass('valid');
            }
            if ($('#password').val() !== '') {
                $('#password').addClass('valid');
                $('#password').removeClass('invalid');
                prontoSenha = true;
            } else {
                prontoSenha = false;
                $('#password').addClass('invalid');
                $('#password').removeClass('valid');
            }
            
            if ( prontoEmail && prontoSenha ) {
                return true;
            } else{
                return false;
            }
        }
    })

</script>
