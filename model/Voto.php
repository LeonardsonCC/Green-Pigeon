<?php

/**
* classe Voto
*
* @author Instalador LazyPHP <http://lazyphp.com.br>
* @version 05/10/2018 11:29
*/
final class Voto extends Record{ 

    const TABLE = 'voto';
    const PK = 'id';
    
    public $id;
    public $usuario_id;
    public $ponto_id;
    public $valor;
    
    /**
    * Configurações e filtros globais do modelo
    * @return Criteria $criteria
    */
    public static function configure(){
        $criteria = new Criteria();
        $criteria->paginate(20, 'paginaVoto');
        return $criteria;
    }
    
    /**
    * Sanitize - filtra os caracteres válidos para cada atributo
    * Configure corretamente por questão de segurança (XSS)
    * Este método é chamado automaticamente pelo método save() da superclasse
    */
    public function sanitize(){
            $this->id = 	filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);
            $this->usuario_id = 	filter_var($this->usuario_id, FILTER_SANITIZE_NUMBER_INT);
            $this->ponto_id = 	filter_var($this->ponto_id, FILTER_SANITIZE_NUMBER_INT);
            $this->valor = 	filter_var($this->valor, FILTER_SANITIZE_NUMBER_INT);
    }
    
    /**
    * Voto pertence a Ponto_coleta
    * @return Ponto_coleta $Ponto_coleta
    */
    function getPonto_coleta() {
        return $this->belongsTo('Ponto_coleta','ponto_id');
    }
    
    /**
    * Voto pertence a Usuario
    * @return Usuario $Usuario
    */
    function getUsuario() {
        return $this->belongsTo('Usuario','usuario_id');
    }
}