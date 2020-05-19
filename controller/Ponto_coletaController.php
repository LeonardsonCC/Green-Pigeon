<?php

/**
* classe Ponto_coletaController
*
* @author Instalador LazyPHP <http://lazyphp.com.br>
* @version 06/09/2018 21:38
*/
final class Ponto_ColetaController extends AppController{ 

    # página inicial do módulo Ponto_coleta
    function inicio(){
        $this->setTitle('Ponto_coleta');
    }

    # lista de Ponto_coletas
    # renderiza a visão /view/Ponto_coleta/lista.php
    function lista(){
        $this->setTitle('Ponto_coletas');
        $c = new Criteria();
        if ( $this->getParam('pesquisa') ) {
            $c->addCondition('nome', 'LIKE', '%' . $this->getParam('pesquisa') . '%');
        }
        if ($this->getParam('ordenaPor')) {
            $c->setOrder($this->getParam('ordenaPor'));
        }
        $this->set('Ponto_coletas', Ponto_coleta::getList($c));
    }

    # visualiza um(a) Ponto_coleta
    # renderiza a visão /ver/Ponto_coleta/ver.php
    function ver(){
        try {
            $Ponto_coleta = new Ponto_coleta( (int)$this->getParam(0) );
            $this->set('Ponto_coleta', $Ponto_coleta);
            $this->setTitle($Ponto_coleta->nome);
        } catch (Exception $e) {
            new Msg($e->getMessage(), 2);
            $this->go('Ponto_coleta', 'lista');
        }
    }

    # formulário de cadastro de Ponto_coleta
    # renderiza a visão /view/Ponto_coleta/cadastrar.php
    function cadastrar(){
        $this->setTitle('Cadastrar Ponto_coleta');
        $this->set('Ponto_coleta', new Ponto_coleta);
        $this->set('Categorias',  Categoria::getList());
        $this->set('Usuarios',  Usuario::getList());
        $latitude = filter_input(INPUT_GET, 'lat');
        $this->set('latitude', $latitude);
    }

    # recebe os dados enviados via post do cadastro de Ponto_coleta
    # (true)redireciona ou (false) renderiza a visão /view/Ponto_coleta/cadastrar.php
    function post_cadastrar(){
        $this->setTitle('Cadastrar Ponto_coleta');
        $Ponto_coleta = new Ponto_coleta();
        $this->set('Ponto_coleta', $Ponto_coleta);
        try {
            $Ponto_coleta->id = filter_input(INPUT_POST , 'id');
            $Ponto_coleta->nome = filter_input(INPUT_POST , 'nome');
            $Ponto_coleta->latitude = filter_input(INPUT_POST , 'latitude');
            $Ponto_coleta->longitude = filter_input(INPUT_POST , 'longitude');
            $Ponto_coleta->descricao = filter_input(INPUT_POST , 'descricao');
            $Ponto_coleta->exibir = FALSE;
            $Ponto_coleta->aprovacoes = 0;
            $Ponto_coleta->desaprovacoes = 0;
            $Ponto_coleta->data_criacao = date(date("Y-m-d"));
            $Ponto_coleta->usuario_id = Session::get('user');
            $Ponto_coleta->categoria_id = filter_input(INPUT_POST , 'categoria_id');
            $Ponto_coleta->save();
            new Msg('Ponto_coleta salvo(a)!');
        } catch (Exception $e) {
            new Msg($e->getMessage(),3);
        }
        if(filter_input(INPUT_POST , 'url_origem')){
            $this->goUrl(Cript::decript(filter_input(INPUT_POST , 'url_origem')));
        }
        $this->go('Ponto_coleta', 'lista');
        $this->set('Categorias',  Categoria::getList());
        $this->set('Usuarios',  Usuario::getList());
    }

    # formulário de edição de Ponto_coleta
    # renderiza a visão /view/Ponto_coleta/editar.php
    function editar(){
        $this->setTitle('Editar Ponto_coleta');
        try {
            $this->set('Ponto_coleta', new Ponto_coleta((int) $this->getParam(0)));
            $this->set('Categorias',  Categoria::getList());
            $this->set('Usuarios',  Usuario::getList());
        } catch (Exception $e) {
            new Msg($e->getMessage(),3);
            $this->go('Ponto_coleta', 'lista');
        }
    }

    # recebe os dados enviados via post da edição de Ponto_coleta
    # (true)redireciona ou (false) renderiza a visão /view/Ponto_coleta/editar.php
    function post_editar(){
        $this->setTitle('Editar Ponto_coleta');
        try {
            $Ponto_coleta = new Ponto_coleta( (int)$this->getParam(0) );
            $this->set('Ponto_coleta', $Ponto_coleta);
            $Ponto_coleta->nome = filter_input(INPUT_POST , 'nome');
            $Ponto_coleta->latitude = filter_input(INPUT_POST , 'latitude');
            $Ponto_coleta->longitude = filter_input(INPUT_POST , 'longitude');
            $Ponto_coleta->descricao = filter_input(INPUT_POST , 'descricao');
            $Ponto_coleta->exibir = (bool)filter_input(INPUT_POST , 'exibir' );
            $Ponto_coleta->aprovacoes = filter_input(INPUT_POST , 'aprovacoes');
            $Ponto_coleta->desaprovacoes = filter_input(INPUT_POST , 'desaprovacoes');
            $Ponto_coleta->data_criacao = filter_input(INPUT_POST , 'data_criacao');
            $Ponto_coleta->usuario_id = filter_input(INPUT_POST , 'usuario_id');
            $Ponto_coleta->categoria_id = filter_input(INPUT_POST , 'categoria_id');
            $Ponto_coleta->save();
            new Msg('Atualização concluída!');
        } catch (Exception $e) {
            new Msg('A atualização não foi concluída! '.$e->getMessage(),3);
        }
        if(filter_input(INPUT_POST , 'url_origem')){
            $this->goUrl(Cript::decript(filter_input(INPUT_POST , 'url_origem')));
        }
        $this->go('Ponto_coleta', 'lista');
        $this->set('Categorias',  Categoria::getList());
        $this->set('Usuarios',  Usuario::getList());
    }

    # Confirma a exclusão ou não de um(a) Ponto_coleta
    # renderiza a /view/Ponto_coleta/apagar.php
    function apagar(){
        $this->setTitle('Apagar Ponto_coleta');
        try {
            $this->set('Ponto_coleta', new Ponto_coleta((int)$this->getParam(0)));
        } catch (Exception $e) {
            new Msg($e->getMessage(), 2);
            $this->go('Ponto_coleta', 'lista');
        }
    }

    # Recebe o id via post e exclui um(a) Ponto_coleta
    # redireciona para Ponto_coleta/lista
    function post_apagar(){
        try {
            $Ponto_coleta = new Ponto_coleta((int) filter_input(INPUT_POST , 'id'));
            $Ponto_coleta->delete();
            new Msg('Ponto_coleta excluído(a)!', 1);
        } catch (Exception $e) {
            new Msg($e->getMessage(),3);
        }
        if(filter_input(INPUT_POST , 'url_origem')){
            $this->goUrl(Cript::decript(filter_input(INPUT_POST , 'url_origem')));
        }
        $this->go('Ponto_coleta', 'lista');
    }
    
    function mapa(){
        $this->setTitle('Mapa - Green Pigeon');
        $c = new Criteria();
        if ( $this->getParam('pesquisa') ) {
            $c->addCondition('nome', 'LIKE', '%' . $this->getParam('pesquisa') . '%');
        }
        if ($this->getParam('ordenaPor')) {
            $c->setOrder($this->getParam('ordenaPor'));
        }
        $this->set('Ponto_coletas', Ponto_Coleta::getList($c));
    }
}
