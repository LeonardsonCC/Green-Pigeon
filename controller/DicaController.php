<?php

/**
 * classe DicaController
 *
 * @author Instalador LazyPHP <http://lazyphp.com.br>
 * @version 06/09/2018 21:38
 */
final class DicaController extends AppController {
    # página inicial do módulo Dica

    function inicio() {
        $this->setTitle('Dica');
    }

    # lista de Dicas
    # renderiza a visão /view/Dica/lista.php

    function lista() {
        $this->setTitle('Dicas');
        $c = new Criteria();
        if ($this->getParam('pesquisa')) {
            $c->addCondition('titulo', 'LIKE', '%' . $this->getParam('pesquisa') . '%');
        }
        if ($this->getParam('ordenaPor')) {
            $c->setOrder($this->getParam('ordenaPor'));
        }
        if ($this->getParam('usuario')) {
            $c->addCondition('criador_id', '=', $this->getParam('usuario'));
        }
        $this->set('Dicas', Dica::getList($c));
    }

    # visualiza um(a) Dica
    # renderiza a visão /ver/Dica/ver.php

    function ver() {
        try {
            $Dica = new Dica((int) $this->getParam(0));
            $this->set('Dica', $Dica);
            $this->setTitle($Dica->titulo);
        } catch (Exception $e) {
            new Msg($e->getMessage(), 2);
            $this->go('Dica', 'lista');
        }
    }

    # formulário de cadastro de Dica
    # renderiza a visão /view/Dica/cadastrar.php

    function cadastrar() {
        $this->setTitle('Cadastrar Dica');
        $this->set('Dica', new Dica);
        $this->set('Categorias', Categoria::getList());
        $this->set('Usuarios', Usuario::getList());
    }

    # recebe os dados enviados via post do cadastro de Dica
    # (true)redireciona ou (false) renderiza a visão /view/Dica/cadastrar.php

    function post_cadastrar() {
        $this->setTitle('Cadastrar Dica');
        $Dica = new Dica();
        $this->set('Dica', $Dica);
        try {
            $Dica->id = filter_input(INPUT_POST, 'id');
            $Dica->titulo = filter_input(INPUT_POST, 'titulo');
            $Dica->texto = filter_input(INPUT_POST, 'texto');
            $Dica->criador_id = filter_input(INPUT_POST, 'criador_id');
            $Dica->data_criacao = date("Y-m-d");
            $Dica->categoria_id = filter_input(INPUT_POST, 'categoria_id');
            $Dica->save();
            new Msg('Dica salvo(a)!');
        } catch (Exception $e) {
            new Msg($e->getMessage(), 3);
        }
        if (filter_input(INPUT_POST, 'url_origem')) {
            $this->goUrl(Cript::decript(filter_input(INPUT_POST, 'url_origem')));
        }
        $this->go('Dica', 'lista');
        $this->set('Categorias', Categoria::getList());
        $this->set('Usuarios', Usuario::getList());
    }

    # formulário de edição de Dica
    # renderiza a visão /view/Dica/editar.php

    function editar() {
        $this->setTitle('Editar Dica');
        try {
            $this->set('Dica', new Dica((int) $this->getParam(0)));
            $this->set('Categorias', Categoria::getList());
            $this->set('Usuarios', Usuario::getList());
        } catch (Exception $e) {
            new Msg($e->getMessage(), 3);
            $this->go('Dica', 'lista');
        }
    }

    # recebe os dados enviados via post da edição de Dica
    # (true)redireciona ou (false) renderiza a visão /view/Dica/editar.php

    function post_editar() {
        $this->setTitle('Editar Dica');
        try {
            $Dica = new Dica((int) $this->getParam(0));
            $this->set('Dica', $Dica);
            $Dica->titulo = filter_input(INPUT_POST, 'titulo');
            $Dica->texto = filter_input(INPUT_POST, 'texto');
            $Dica->criador_id = filter_input(INPUT_POST, 'criador_id');
            $Dica->data_criacao = filter_input(INPUT_POST, 'data_criacao');
            $Dica->categoria_id = filter_input(INPUT_POST, 'categoria_id');
            $Dica->save();
            new Msg('Atualização concluída!');
        } catch (Exception $e) {
            new Msg('A atualização não foi concluída! ' . $e->getMessage(), 3);
        }
        if (filter_input(INPUT_POST, 'url_origem')) {
            $this->goUrl(Cript::decript(filter_input(INPUT_POST, 'url_origem')));
        }
        $this->go('Dica', 'lista');
        $this->set('Categorias', Categoria::getList());
        $this->set('Usuarios', Usuario::getList());
    }

    # Confirma a exclusão ou não de um(a) Dica
    # renderiza a /view/Dica/apagar.php

    function apagar() {
        $this->setTitle('Apagar Dica');
        try {
            $this->set('Dica', new Dica((int) $this->getParam(0)));
        } catch (Exception $e) {
            new Msg($e->getMessage(), 2);
            $this->go('Dica', 'lista');
        }
    }

    # Recebe o id via post e exclui um(a) Dica
    # redireciona para Dica/lista

    function post_apagar() {
        try {
            $Dica = new Dica((int) filter_input(INPUT_POST, 'id'));
            $Dica->delete();
            new Msg('Dica excluído(a)!', 1);
        } catch (Exception $e) {
            new Msg($e->getMessage(), 3);
        }
        if (filter_input(INPUT_POST, 'url_origem')) {
            $this->goUrl(Cript::decript(filter_input(INPUT_POST, 'url_origem')));
        }
        $this->go('Dica', 'lista');
    }

    # Lista para usuários comuns

    function dicas() {
        $this->setTitle('Dicas - Green Pigeon');
        $c = new Criteria();
        if ($this->getParam(0)) {
            $this->set('Categoria', new Categoria((int) $this->getParam(0)));
        }
        else{
            $this->go('Dica', 'dicas', array(1));
        }
        $c->addCondition('categoria_id', '=', $this->getParam(0));
        if( $this->getParam(1) ){
            $this->set('Dica', new Dica((int) $this->getParam(1)));
        }
        
        $this->set('Categorias', Categoria::getList());
        $this->set('Dicas', Dica::getList($c));
    }

}
