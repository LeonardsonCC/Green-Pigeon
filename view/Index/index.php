<div class="container" style="margin-bottom: 70px;">
    <div class="row">
        <!-- Titulo -->
        <h1 class="col s12 m12 l12 xl12 center-align white-text titulo hide-on-med-and-down">Green Pigeon</h1>
    </div>
    <div class="row">
        <div class="col s12 m6 l6 xl6 white-text" id="entrar-anuncio">
            <!-- Anuncio -->
            <div class="center-align" id="anuncio">
                <img id="img-anuncio" class="circle z-depth-5 " src="<?php echo SITE_PATH; ?>/template/default/images/gp-icone.png">
                <p id="text-anuncio" class="center-align">Encontre os pontos de coleta seletiva mais próximos a você</p>
                <?php
                echo $this->Html->getLink('Abrir o mapa', 'Ponto_coleta', 'mapa', NULL, array('class' => 'waves-effect waves-light btn-large green darken-2 hide-on-med-and-down'));
                ?>
            </div>
        </div>
        <div class="col s1 m1 l1 xl1"></div>
        <?php
        if (!Session::get('user')) {
            echo '<div class="col s12 m6 l5 xl5 white-text" id="entrar-inicio">
            <!-- Login -->
            <div class="center-align" id="formulario-entrar">
                <h3>Entrar</h3>
                <form action="' . $this->Html->getUrl('Login', 'login') . '"  id="formulario-entrar-form" method="post">
                    <div class="input-field col s12 l12 xl12">
                        <input id="login" name="login" type="text" class="white-text">
                        <label for="login">E-mail</label>
                    </div>
                    <div class="input-field col s12 l12 xl12">
                        <input id="password" class="white-text" name="password" type="password">
                        <label for="password">Senha</label>
                    </div>
                    <p>
                        <a href="'.$this->Html->getUrl('Login', 'send').'">Esqueceu sua senha?</a>
                    </p>
                    <button type="submit" class="waves-effect waves-light btn-large green darken-2">Entrar</button>
                </form>
                <p>
                    Não tem uma conta ainda? <a href="#" class="alternar-login">Registre-se</a> agora mesmo!
                </p>

            </div>
            <!-- Registrar -->
            <div class="center-align" id="formulario-cadastrar">
                <h3>Registrar-se</h3>
                <form action="' . $this->Html->getUrl('Usuario', 'cadastrar') .'" method="post" id="registrar-form">
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
                <p>
                    Já tem uma conta? <a href="#" class="alternar-registrar">Entre</a> aqui!
                </p>

            </div>
        </div>';
        } else {
            
            echo '<div class="col s12 m6 l5 xl5">
                <div class="col s12 m12 l12 white-text center-align">
                    <h3>Você pode agora adicionar pontos de coleta</h3>
                    <p> Vá para a página de mapa e adicione algum ponto que você conhece,
                    essa informação pode ser útil para alguém,
                    contribua com a comunidade!
                    </p>
                    </div>
            </div>';            
        }
        ?>
        <div class="col s12 m12 l12">
            <h2 class="center white-text titulo">Eventos</h2>
            <?php
            foreach( $Eventos as $e ){
                $data = date_create($e->data_evento);
                $data = date_format($data, 'd/m/Y');
                echo '
                <div class="col s12 m12 hide-on-med-and-down">
                    <div class="card small horizontal hoverable" style="height: 200px;">
                      <div class="card-image">
                        <img src="'.SITE_PATH.'/'.$e->capa.'">
                      </div>
                      <div class="card-stacked">
                        <div class="card-content">
                          <h4 class="left">'.$e->titulo.'</h4> <span class="right" style="font-size: 10px">'.$data.'</span>
                          <div class="clearfix"></div>
                          <p>'. $e->texto .'</p>
                        </div>
                        <div class="card-action right-align white">
                          <a class="blue-text" href="'.$this->Html->getUrl('Evento', 'ver', array($e->id)).'">Ler mais </a>
                        </div>
                      </div>
                    </div>
                  </div>    
                ';
                echo '<div class="row">
                <div class="col s12 hide-on-med-and-up">
                  <div class="card">
                    <div class="card-image">
                      <img src="'.SITE_PATH.'/'.$e->capa.'">
                      <span class="card-title">'.$e->titulo.'</span>
                    </div>
                    <div class="card-content">
                      <p>'.substr($e->texto, 0, 150).'...</p>
                    </div>
                    <div class="card-action">
                    <a class="blue-text" href="'.$this->Html->getUrl('Evento', 'ver', array($e->id)).'">Ler mais </a>
                    </div>
                  </div>
                </div>
              </div>';
            }
            ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#registrar-form').submit(function (e) {
            if (!formCheckRegistrar()) {
                e.preventDefault();
                M.toast({html: 'Verifique os campos para cadastro!', classes: 'red white-text'});
            }
        });

        $('#formulario-entrar-form').submit(function (e) {
            if (!formCheckEntrar()) {
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
            } else {
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

            if (prontoEmail && prontoSenha) {
                return true;
            } else {
                return false;
            }
        }
    })
</script>