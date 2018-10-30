<?php

/**
 * classe UsuarioController
 *
 * @author Instalador LazyPHP <http://lazyphp.com.br>
 * @version 06/09/2018 21:38
 */
final class UsuarioController extends AppController {
    # página inicial do módulo Usuario

    function inicio() {
        $this->setTitle('Usuario');
    }

    # lista de Usuarios
    # renderiza a visão /view/Usuario/lista.php

    function lista() {
        $this->setTitle('Usuarios');
        $c = new Criteria();
        if ($this->getParam('pesquisa')) {
            $c->addCondition('email', 'LIKE', '%' . $this->getParam('pesquisa') . '%');
        }
        if ($this->getParam('ordenaPor')) {
            $c->setOrder($this->getParam('ordenaPor'));
        }
        $this->set('Usuarios', Usuario::getList($c));
    }

    # visualiza um(a) Usuario
    # renderiza a visão /ver/Usuario/ver.php

    function ver() {
        try {
            $Usuario = new Usuario((int) $this->getParam(0));
            $this->set('Usuario', $Usuario);
            $this->set('Dicas', $Usuario->getDicas());
            $this->set('DicaCategorias', $Usuario->getDicaCategorias());
            $this->set('Ponto_coletas', $Usuario->getPonto_coletas());
            $this->set('Ponto_coletaCategorias', $Usuario->getPonto_coletaCategorias());
            $this->setTitle($Usuario->email);
        } catch (Exception $e) {
            new Msg($e->getMessage(), 2);
            $this->go('Usuario', 'lista');
        }
    }

    # formulário de cadastro de Usuario
    # renderiza a visão /view/Usuario/cadastrar.php

    function cadastrar() {
        $this->setTitle('Cadastrar Usuario');
        $this->set('Usuario', new Usuario);
    }

    # recebe os dados enviados via post do cadastro de Usuario
    # (true)redireciona ou (false) renderiza a visão /view/Usuario/cadastrar.php

    function post_cadastrar() {
        $this->setTitle('Cadastrar Usuario');
        $Usuario = new Usuario();
        $this->set('Usuario', $Usuario);
        try {
            $Usuario->id = filter_input(INPUT_POST, 'id');
            $Usuario->email = filter_input(INPUT_POST, 'email');
            $Usuario->senha = md5(Config::get('salt') . filter_input(INPUT_POST, 'senha'));
            $csenha = md5(Config::get('salt') . filter_input(INPUT_POST, 'csenha'));
            if ($Usuario->senha != $csenha) {
                new Msg('Erro: Senhas diferentes!', 3);
            }
            $Usuario->nome = filter_input(INPUT_POST, 'nome');
            $Usuario->avatar = 'template/default/images/user.png';
            $Usuario->adm = 1;
            $Usuario->pontuacao = 0;
            $Usuario->save();
            new Msg('Usuario salvo(a)!');
        } catch (Exception $e) {
            new Msg($e->getMessage(), 3);
        }
        if (filter_input(INPUT_POST, 'url_origem')) {
            $this->goUrl(Cript::decript(filter_input(INPUT_POST, 'url_origem')));
        }
        $this->go('Index', 'index');
    }

    # formulário de edição de Usuario
    # renderiza a visão /view/Usuario/editar.php

    function editar() {
        $this->setTitle('Editar Usuario');
        try {
            $this->set('Usuario', new Usuario((int) $this->getParam(0)));
        } catch (Exception $e) {
            new Msg($e->getMessage(), 3);
            $this->go('Usuario', 'lista');
        }
    }

    # recebe os dados enviados via post da edição de Usuario
    # (true)redireciona ou (false) renderiza a visão /view/Usuario/editar.php

    function post_editar() {
        $this->setTitle('Editar Usuario');
        try {
            $Usuario = new Usuario((int) $this->getParam(0));
            $this->set('Usuario', $Usuario);
            $Usuario->email = filter_input(INPUT_POST, 'email');
            $password = filter_input(INPUT_POST, 'senha');
            if (!empty($password)) {
                $Usuario->senha = md5(Config::get('salt') . $password);
            }
            $Usuario->nome = filter_input(INPUT_POST, 'nome');
            # upload de imagem
            $imagem = $_FILES['avatar'];
            # se foi enviada alguma imagem
            if ($imagem['name']) {
                $iu = new ImageUploader($imagem, 300);
                $Usuario->avatar = $iu->save(uniqid(), 'avatar');
            }
            $Usuario->adm = (bool) filter_input(INPUT_POST, 'adm');
            $Usuario->pontuacao = filter_input(INPUT_POST, 'pontuacao');
            $Usuario->save();
            new Msg('Atualização concluída!');
        } catch (Exception $e) {
            new Msg('A atualização não foi concluída! ' . $e->getMessage(), 3);
        }
        if (filter_input(INPUT_POST, 'url_origem')) {
            $this->goUrl(Cript::decript(filter_input(INPUT_POST, 'url_origem')));
        }
        $this->go('Usuario', 'lista');
    }

    # Confirma a exclusão ou não de um(a) Usuario
    # renderiza a /view/Usuario/apagar.php

    function apagar() {
        $this->setTitle('Apagar Usuario');
        try {
            $this->set('Usuario', new Usuario((int) $this->getParam(0)));
        } catch (Exception $e) {
            new Msg($e->getMessage(), 2);
            $this->go('Usuario', 'lista');
        }
    }

    # Recebe o id via post e exclui um(a) Usuario
    # redireciona para Usuario/lista

    function post_apagar() {
        try {
            $Usuario = new Usuario((int) filter_input(INPUT_POST, 'id'));
            $logado = Session::get('user');
            $Usuario->delete();
            if ($logado->id == $Usuario->id) {
                new Msg('Usuario excluído(a)!', 1);
                new Msg('Você foi deslogado!', 1);
                $this->go('Login', 'logout');
            }
            new Msg('Usuario excluído(a)!', 1);
        } catch (Exception $e) {
            new Msg($e->getMessage(), 3);
        }
        if (filter_input(INPUT_POST, 'url_origem')) {
            $this->goUrl(Cript::decript(filter_input(INPUT_POST, 'url_origem')));
        }
        $this->go('Index', 'index');
    }

}
