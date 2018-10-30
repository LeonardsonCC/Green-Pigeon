<?php

/**
* classe FeedbackController
*
* @author Instalador LazyPHP <http://lazyphp.com.br>
* @version 16/10/2018 19:30
*/
final class FeedbackController extends AppController{ 

    # página inicial do módulo Feedback
    function inicio(){
        $this->setTitle('Feedback');
    }

    # lista de Feedbacks
    # renderiza a visão /view/Feedback/lista.php
    function lista(){
        $this->setTitle('Feedbacks');
        $c = new Criteria();
        if ( $this->getParam('pesquisa') ) {
            $c->addCondition('texto', 'LIKE', '%' . $this->getParam('pesquisa') . '%');
        }
        if ($this->getParam('ordenaPor')) {
            $c->setOrder($this->getParam('ordenaPor'));
        }
        $this->set('Feedbacks', Feedback::getList($c));
    }

    # visualiza um(a) Feedback
    # renderiza a visão /ver/Feedback/ver.php
    function ver(){
        try {
            $Feedback = new Feedback( (int)$this->getParam(0) );
            $this->set('Feedback', $Feedback);
            $this->setTitle($Feedback->texto);
        } catch (Exception $e) {
            new Msg($e->getMessage(), 2);
            $this->go('Feedback', 'lista');
        }
    }

    # formulário de cadastro de Feedback
    # renderiza a visão /view/Feedback/cadastrar.php
    function cadastrar(){
        $this->setTitle('Cadastrar Feedback');
        $this->set('Feedback', new Feedback);
    }

    # recebe os dados enviados via post do cadastro de Feedback
    # (true)redireciona ou (false) renderiza a visão /view/Feedback/cadastrar.php
    function post_cadastrar(){
        $this->setTitle('Cadastrar Feedback');
        $Feedback = new Feedback();
        $this->set('Feedback', $Feedback);
        try {
            $Feedback->id = filter_input(INPUT_POST , 'id');
            $Feedback->texto = filter_input(INPUT_POST , 'texto');
            $Feedback->email = filter_input(INPUT_POST , 'email');
            $Feedback->receber_email = 0;
            if( filter_input(INPUT_POST , 'receber_email') ) {
                $Feedback->receber_email = 1;
            }
            $Feedback->save();
            new Msg('Feedback salvo, obrigado!');
        } catch (Exception $e) {
            new Msg($e->getMessage(),3);
        }
        if(filter_input(INPUT_POST , 'url_origem')){
            $this->goUrl(Cript::decript(filter_input(INPUT_POST , 'url_origem')));
        }
        $this->go('Index', 'index');
    }

    # formulário de edição de Feedback
    # renderiza a visão /view/Feedback/editar.php
    function editar(){
        $this->setTitle('Editar Feedback');
        try {
            $this->set('Feedback', new Feedback((int) $this->getParam(0)));
        } catch (Exception $e) {
            new Msg($e->getMessage(),3);
            $this->go('Feedback', 'lista');
        }
    }

    # recebe os dados enviados via post da edição de Feedback
    # (true)redireciona ou (false) renderiza a visão /view/Feedback/editar.php
    function post_editar(){
        $this->setTitle('Editar Feedback');
        try {
            $Feedback = new Feedback( (int)$this->getParam(0) );
            $this->set('Feedback', $Feedback);
            $Feedback->texto = filter_input(INPUT_POST , 'texto');
            $Feedback->email = filter_input(INPUT_POST , 'email');
            $Feedback->receber_email = filter_input(INPUT_POST , 'receber_email');
            $Feedback->save();
            new Msg('Atualização concluída!');
        } catch (Exception $e) {
            new Msg('A atualização não foi concluída! '.$e->getMessage(),3);
        }
        if(filter_input(INPUT_POST , 'url_origem')){
            $this->goUrl(Cript::decript(filter_input(INPUT_POST , 'url_origem')));
        }
        $this->go('Feedback', 'lista');
    }

    # Confirma a exclusão ou não de um(a) Feedback
    # renderiza a /view/Feedback/apagar.php
    function apagar(){
        $this->setTitle('Apagar Feedback');
        try {
            $this->set('Feedback', new Feedback((int)$this->getParam(0)));
        } catch (Exception $e) {
            new Msg($e->getMessage(), 2);
            $this->go('Feedback', 'lista');
        }
    }

    # Recebe o id via post e exclui um(a) Feedback
    # redireciona para Feedback/lista
    function post_apagar(){
        try {
            $Feedback = new Feedback((int) filter_input(INPUT_POST , 'id'));
            $Feedback->delete();
            new Msg('Feedback excluído(a)!', 1);
        } catch (Exception $e) {
            new Msg($e->getMessage(),3);
        }
        if(filter_input(INPUT_POST , 'url_origem')){
            $this->goUrl(Cript::decript(filter_input(INPUT_POST , 'url_origem')));
        }
        $this->go('Feedback', 'lista');
    }

}