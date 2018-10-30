<?php

/**
 * classe EventoController
 *
 * @author Instalador LazyPHP <http://lazyphp.com.br>
 * @version 17/09/2018 09:07
 */
final class EventoController extends AppController {
    # página inicial do módulo Evento

    function inicio() {
        $this->setTitle('Evento');
    }

    # lista de Eventos
    # renderiza a visão /view/Evento/lista.php

    function lista() {
        $this->setTitle('Eventos');
        $c = new Criteria();
        if ($this->getParam('pesquisa')) {
            $c->addCondition('titulo', 'LIKE', '%' . $this->getParam('pesquisa') . '%');
        }
        if ($this->getParam('ordenaPor')) {
            $c->setOrder($this->getParam('ordenaPor'));
        }
        $this->set('Eventos', Evento::getList($c));
    }

    # visualiza um(a) Evento
    # renderiza a visão /ver/Evento/ver.php

    function ver() {
        try {
            $Evento = new Evento((int) $this->getParam(0));
            $this->set('Evento', $Evento);
            $this->setTitle($Evento->titulo);
        } catch (Exception $e) {
            new Msg($e->getMessage(), 2);
            $this->go('Evento', 'lista');
        }
    }

    # formulário de cadastro de Evento
    # renderiza a visão /view/Evento/cadastrar.php

    function cadastrar() {
        $this->setTitle('Cadastrar Evento');
        $this->set('Evento', new Evento);
        $this->set('Usuarios', Usuario::getList());
    }

    # recebe os dados enviados via post do cadastro de Evento
    # (true)redireciona ou (false) renderiza a visão /view/Evento/cadastrar.php

    function post_cadastrar() {
        $this->setTitle('Cadastrar Evento');
        $Evento = new Evento();
        $this->set('Evento', $Evento);
        try {
            $Evento->id = filter_input(INPUT_POST, 'id');
            $Evento->titulo = filter_input(INPUT_POST, 'titulo');
            $Evento->texto = filter_input(INPUT_POST, 'texto');
            $user = Session::get('user');
            $Evento->criador_id = $user->id;
            # upload de imagem
            $imagem = $_FILES['capa'];
            # se foi enviada alguma imagem
            if ($imagem['name']) {
                $iu = new ImageUploader($imagem, 2080);
                $Evento->capa = $iu->save(uniqid(), 'capa_evento');
            }
            $Evento->data_criacao = date('Y-m-d');
            $Evento->latitude = filter_input(INPUT_POST, 'latitude');
            $Evento->longitude = filter_input(INPUT_POST, 'longitude');
            $Evento->data_evento = filter_input(INPUT_POST, 'data_evento');
            if ($Evento->data_evento) {
                $data_evento = substr($Evento->data_evento, 8, 11) . '-';
                $mes = '';
                switch (substr($Evento->data_evento, 0, 3)) {
                    case 'Jan':
                        $mes = '01';
                        break;
                    case 'Feb':
                        $mes = '02';
                        break;
                    case 'Mar':
                        $mes = '03';
                        break;
                    case 'Apr':
                        $mes = '04';
                        break;
                    case 'May':
                        $mes = '05';
                        break;
                    case 'Jun':
                        $mes = '06';
                        break;
                    case 'Jul':
                        $mes = '07';
                        break;
                    case 'Aug':
                        $mes = '08';
                        break;
                    case 'Sep':
                        $mes = '09';
                        break;
                    case 'Oct':
                        $mes = '10';
                        break;
                    case 'Nov':
                        $mes = '11';
                        break;
                    case 'Dev':
                        $mes = '11';
                        break;
                }
                $data_evento .= $mes;
                $data_evento .= '-'.substr($Evento->data_evento, 4, 5);
            }
            $Evento->data_evento = $data_evento;
            $Evento->save();
            new Msg('Evento salvo(a)!');
        } catch (Exception $e) {
            new Msg($e->getMessage(), 3);
        }
        if (filter_input(INPUT_POST, 'url_origem')) {
            $this->goUrl(Cript::decript(filter_input(INPUT_POST, 'url_origem')));
        }
        $this->go('Evento', 'lista');
        $this->set('Usuarios', Usuario::getList());
    }

    # formulário de edição de Evento
    # renderiza a visão /view/Evento/editar.php

    function editar() {
        $this->setTitle('Editar Evento');
        try {
            $this->set('Evento', new Evento((int) $this->getParam(0)));
            $this->set('Usuarios', Usuario::getList());
        } catch (Exception $e) {
            new Msg($e->getMessage(), 3);
            $this->go('Evento', 'lista');
        }
    }

    # recebe os dados enviados via post da edição de Evento
    # (true)redireciona ou (false) renderiza a visão /view/Evento/editar.php

    function post_editar() {
        $this->setTitle('Editar Evento');
        try {
            $Evento = new Evento((int) $this->getParam(0));
            $this->set('Evento', $Evento);
            $Evento->titulo = filter_input(INPUT_POST, 'titulo');
            $Evento->texto = filter_input(INPUT_POST, 'texto');
            $Evento->criador_id = filter_input(INPUT_POST, 'criador_id');
            $Evento->capa = filter_input(INPUT_POST, 'capa');
            $Evento->data_criacao = filter_input(INPUT_POST, 'data_criacao');
            $Evento->latitude = filter_input(INPUT_POST, 'latitude');
            $Evento->longitude = filter_input(INPUT_POST, 'longitude');
            $Evento->save();
            new Msg('Atualização concluída!');
        } catch (Exception $e) {
            new Msg('A atualização não foi concluída! ' . $e->getMessage(), 3);
        }
        if (filter_input(INPUT_POST, 'url_origem')) {
            $this->goUrl(Cript::decript(filter_input(INPUT_POST, 'url_origem')));
        }
        $this->go('Evento', 'lista');
        $this->set('Usuarios', Usuario::getList());
    }

    # Confirma a exclusão ou não de um(a) Evento
    # renderiza a /view/Evento/apagar.php

    function apagar() {
        $this->setTitle('Apagar Evento');
        try {
            $this->set('Evento', new Evento((int) $this->getParam(0)));
        } catch (Exception $e) {
            new Msg($e->getMessage(), 2);
            $this->go('Evento', 'lista');
        }
    }

    # Recebe o id via post e exclui um(a) Evento
    # redireciona para Evento/lista

    function post_apagar() {
        try {
            $Evento = new Evento((int) filter_input(INPUT_POST, 'id'));
            $Evento->delete();
            new Msg('Evento excluído(a)!', 1);
        } catch (Exception $e) {
            new Msg($e->getMessage(), 3);
        }
        if (filter_input(INPUT_POST, 'url_origem')) {
            $this->goUrl(Cript::decript(filter_input(INPUT_POST, 'url_origem')));
        }
        $this->go('Evento', 'lista');
    }

}
