<?php

/**
 * classe VotoController
 *
 * @author Instalador LazyPHP <http://lazyphp.com.br>
 * @version 05/10/2018 11:29
 */
final class VotoController extends AppController {
    # página inicial do módulo Voto

    function inicio() {
        $this->setTitle('Voto');
    }

    # lista de Votos
    # renderiza a visão /view/Voto/lista.php

    function lista() {
        $this->setTitle('Votos');
        $c = new Criteria();
        if ($this->getParam('pesquisa')) {
            $c->addCondition('usuario_id', 'LIKE', '%' . $this->getParam('pesquisa') . '%');
        }
        if ($this->getParam('ordenaPor')) {
            $c->setOrder($this->getParam('ordenaPor'));
        }
        $this->set('Votos', Voto::getList($c));
    }

    # visualiza um(a) Voto
    # renderiza a visão /ver/Voto/ver.php

    function ver() {
        try {
            $Voto = new Voto((int) $this->getParam(0));
            $this->set('Voto', $Voto);
            $this->setTitle($Voto->usuario_id);
        } catch (Exception $e) {
            new Msg($e->getMessage(), 2);
            $this->go('Voto', 'lista');
        }
    }

    # formulário de cadastro de Voto
    # renderiza a visão /view/Voto/cadastrar.php

    function cadastrar() {
        $this->setTitle('Cadastrar Voto');
        $usuario = Session::get('user');
        $c = new Criteria();
        $c->addCondition('usuario_id', '=', $usuario->id);
        $this->set('Votos', Voto::getList($c));
        $this->set('Ponto_coletas', Ponto_coleta::getList());
        $this->set('Usuarios', Usuario::getList());
        $this->set('usuario', $usuario);
    }

    # recebe os dados enviados via post do cadastro de Voto
    # (true)redireciona ou (false) renderiza a visão /view/Voto/cadastrar.php

    function post_cadastrar() {
        $this->setTitle('Cadastrar Voto');
        $Voto = new Voto();
        $this->set('Voto', $Voto);
        try {
            $Voto->id = filter_input(INPUT_POST, 'id');
            $Voto->usuario_id = filter_input(INPUT_POST, 'usuario_id');
            $Voto->ponto_id = filter_input(INPUT_POST, 'ponto_id');
            $Voto->valor = filter_input(INPUT_POST, 'valor');
            $Voto->save();
            new Msg('Voto salvo(a)!');
        } catch (Exception $e) {
            new Msg($e->getMessage(), 3);
        }
        if (filter_input(INPUT_POST, 'url_origem')) {
            $this->goUrl(Cript::decript(filter_input(INPUT_POST, 'url_origem')));
        }
        $this->go('Voto', 'lista');
        $this->set('Ponto_coletas', Ponto_coleta::getList());
        $this->set('Usuarios', Usuario::getList());
    }

    # formulário de edição de Voto
    # renderiza a visão /view/Voto/editar.php

    function editar() {
        $this->setTitle('Editar Voto');
        try {
            $this->set('Voto', new Voto((int) $this->getParam(0)));
            $this->set('Ponto_coletas', Ponto_coleta::getList());
            $this->set('Usuarios', Usuario::getList());
        } catch (Exception $e) {
            new Msg($e->getMessage(), 3);
            $this->go('Voto', 'lista');
        }
    }

    # recebe os dados enviados via post da edição de Voto
    # (true)redireciona ou (false) renderiza a visão /view/Voto/editar.php

    function post_editar() {
        $this->setTitle('Editar Voto');
        try {
            $Voto = new Voto((int) $this->getParam(0));
            $this->set('Voto', $Voto);
            $Voto->usuario_id = filter_input(INPUT_POST, 'usuario_id');
            $Voto->ponto_id = filter_input(INPUT_POST, 'ponto_id');
            $Voto->valor = filter_input(INPUT_POST, 'valor');
            $Voto->save();
            new Msg('Atualização concluída!');
        } catch (Exception $e) {
            new Msg('A atualização não foi concluída! ' . $e->getMessage(), 3);
        }
        if (filter_input(INPUT_POST, 'url_origem')) {
            $this->goUrl(Cript::decript(filter_input(INPUT_POST, 'url_origem')));
        }
        $this->go('Voto', 'lista');
        $this->set('Ponto_coletas', Ponto_coleta::getList());
        $this->set('Usuarios', Usuario::getList());
    }

    # Confirma a exclusão ou não de um(a) Voto
    # renderiza a /view/Voto/apagar.php

    function apagar() {
        $this->setTitle('Apagar Voto');
        try {
            $this->set('Voto', new Voto((int) $this->getParam(0)));
        } catch (Exception $e) {
            new Msg($e->getMessage(), 2);
            $this->go('Voto', 'lista');
        }
    }

    # Recebe o id via post e exclui um(a) Voto
    # redireciona para Voto/lista

    function post_apagar() {
        try {
            $Voto = new Voto((int) filter_input(INPUT_POST, 'id'));
            $Voto->delete();
            new Msg('Voto excluído(a)!', 1);
        } catch (Exception $e) {
            new Msg($e->getMessage(), 3);
        }
        if (filter_input(INPUT_POST, 'url_origem')) {
            $this->goUrl(Cript::decript(filter_input(INPUT_POST, 'url_origem')));
        }
        $this->go('Voto', 'lista');
    }

    function votar() {
        $this->setTitle('Votar');
        $this->set('Votos', Voto::getList());
        $this->set('Ponto_coleta', $this->getParam(0));
        $this->set('Usuarios', Usuario::getList());
    }

    function post_votar() {
        $this->setTitle('Votar');
        $Voto = new Voto();
        $this->set('Voto', $Voto);
        try {
            $Voto->id = filter_input(INPUT_POST, 'id');
            $user = Session::get('user');
            $Voto->usuario_id = $user->id;
            $Voto->ponto_id = filter_input(INPUT_POST, 'ponto_id');
            $Voto->valor = filter_input(INPUT_POST, 'valor');
            $Voto->save();
            new Msg('Voto salvo(a)!');
        } catch (Exception $e) {
            new Msg($e->getMessage(), 3);
        }
        if (filter_input(INPUT_POST, 'url_origem')) {
            $this->goUrl(Cript::decript(filter_input(INPUT_POST, 'url_origem')));
        }
        $this->go('Voto', 'cadastrar');
        $this->set('Ponto_coletas', Ponto_coleta::getList());
        $this->set('Usuarios', Usuario::getList());
    }

}
