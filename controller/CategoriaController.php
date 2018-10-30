<?php

/**
* classe CategoriaController
*
* @author Instalador LazyPHP <http://lazyphp.com.br>
* @version 06/09/2018 21:38
*/
final class CategoriaController extends AppController{ 

    # página inicial do módulo Categoria
    function inicio(){
        $this->setTitle('Categoria');
    }

    # lista de Categorias
    # renderiza a visão /view/Categoria/lista.php
    function lista(){
        $this->setTitle('Categorias');
        $c = new Criteria();
        if ( $this->getParam('pesquisa') ) {
            $c->addCondition('nome', 'LIKE', '%' . $this->getParam('pesquisa') . '%');
        }
        if ($this->getParam('ordenaPor')) {
            $c->setOrder($this->getParam('ordenaPor'));
        }
        $this->set('Categorias', Categoria::getList($c));
    }

    # visualiza um(a) Categoria
    # renderiza a visão /ver/Categoria/ver.php
    function ver(){
        try {
            $Categoria = new Categoria( (int)$this->getParam(0) );
            $this->set('Categoria', $Categoria);
            $this->set('Dicas',$Categoria->getDicas());
            $this->set('DicaUsuarios',$Categoria->getDicaUsuarios());
            $this->set('Ponto_coletas',$Categoria->getPonto_coletas());
            $this->set('Ponto_coletaUsuarios',$Categoria->getPonto_coletaUsuarios());
            $this->setTitle($Categoria->nome);
        } catch (Exception $e) {
            new Msg($e->getMessage(), 2);
            $this->go('Categoria', 'lista');
        }
    }

    # formulário de cadastro de Categoria
    # renderiza a visão /view/Categoria/cadastrar.php
    function cadastrar(){
        $this->setTitle('Cadastrar Categoria');
        $this->set('Categoria', new Categoria);
    }

    # recebe os dados enviados via post do cadastro de Categoria
    # (true)redireciona ou (false) renderiza a visão /view/Categoria/cadastrar.php
    function post_cadastrar(){
        $this->setTitle('Cadastrar Categoria');
        $Categoria = new Categoria();
        $this->set('Categoria', $Categoria);
        try {
            $Categoria->id = filter_input(INPUT_POST , 'id');
            $Categoria->nome = filter_input(INPUT_POST , 'nome');
            # upload de imagem
            $imagem = $_FILES['link'];
            # se foi enviada alguma imagem
            if( $imagem['name'] ){
                $iu = new ImageUploader($imagem, 300);
                $Categoria->link = $iu->save(uniqid(), 'icones-categoria');
            }
            $Categoria->save();
            new Msg('Categoria salvo(a)!');
        } catch (Exception $e) {
            new Msg($e->getMessage(),3);
        }
        if(filter_input(INPUT_POST , 'url_origem')){
            $this->goUrl(Cript::decript(filter_input(INPUT_POST , 'url_origem')));
        }
        $this->go('Categoria', 'lista');
    }

    # formulário de edição de Categoria
    # renderiza a visão /view/Categoria/editar.php
    function editar(){
        $this->setTitle('Editar Categoria');
        try {
            $this->set('Categoria', new Categoria((int) $this->getParam(0)));
        } catch (Exception $e) {
            new Msg($e->getMessage(),3);
            $this->go('Categoria', 'lista');
        }
    }

    # recebe os dados enviados via post da edição de Categoria
    # (true)redireciona ou (false) renderiza a visão /view/Categoria/editar.php
    function post_editar(){
        $this->setTitle('Editar Categoria');
        try {
            $Categoria = new Categoria( (int)$this->getParam(0) );
            $this->set('Categoria', $Categoria);
            $Categoria->nome = filter_input(INPUT_POST , 'nome');
            $Categoria->link = filter_input(INPUT_POST , 'link');
            $Categoria->save();
            new Msg('Atualização concluída!');
        } catch (Exception $e) {
            new Msg('A atualização não foi concluída! '.$e->getMessage(),3);
        }
        if(filter_input(INPUT_POST , 'url_origem')){
            $this->goUrl(Cript::decript(filter_input(INPUT_POST , 'url_origem')));
        }
        $this->go('Categoria', 'lista');
    }

    # Confirma a exclusão ou não de um(a) Categoria
    # renderiza a /view/Categoria/apagar.php
    function apagar(){
        $this->setTitle('Apagar Categoria');
        try {
            $this->set('Categoria', new Categoria((int)$this->getParam(0)));
        } catch (Exception $e) {
            new Msg($e->getMessage(), 2);
            $this->go('Categoria', 'lista');
        }
    }

    # Recebe o id via post e exclui um(a) Categoria
    # redireciona para Categoria/lista
    function post_apagar(){
        try {
            $Categoria = new Categoria((int) filter_input(INPUT_POST , 'id'));
            $Categoria->delete();
            new Msg('Categoria excluído(a)!', 1);
        } catch (Exception $e) {
            new Msg($e->getMessage(),3);
        }
        if(filter_input(INPUT_POST , 'url_origem')){
            $this->goUrl(Cript::decript(filter_input(INPUT_POST , 'url_origem')));
        }
        $this->go('Categoria', 'lista');
    }

}