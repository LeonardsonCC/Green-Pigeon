<?php

class IndexController extends AppController {

    public function index() {
        $c = new Criteria();
        $data = date('Y-m-d');
        $c->addCondition('data_evento', '>=', $data);
        $c->setOrder('data_evento');
        $this->set('Eventos', Evento::getList( $c ));
        $this->setTitle('Início - Green Pigeon');
    }

}
