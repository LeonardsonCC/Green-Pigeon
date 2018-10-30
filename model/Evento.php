<?php

/**
* classe Evento
*
* @author Instalador LazyPHP <http://lazyphp.com.br>
* @version 17/09/2018 09:07
*/
final class Evento extends Record{ 

    const TABLE = 'evento';
    const PK = 'id';
    
    public $id;
    public $titulo;
    public $texto;
    public $criador_id;
    public $capa;
    public $data_criacao;
    public $latitude;
    public $longitude;
    public $data_evento;
    
    /**
    * Configurações e filtros globais do modelo
    * @return Criteria $criteria
    */
    public static function configure(){
        $criteria = new Criteria();
        $criteria->paginate(20, 'paginaEvento');
        return $criteria;
    }
    
    /**
    * Sanitize - filtra os caracteres válidos para cada atributo
    * Configure corretamente por questão de segurança (XSS)
    * Este método é chamado automaticamente pelo método save() da superclasse
    */
    public function sanitize(){
            $this->id = 	filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);
            $this->titulo = 	htmlspecialchars($this->titulo, ENT_QUOTES, "UTF-8");
            $this->texto = 	$this->htmlFilter($this->texto); # vide /lib/core/Record.php
            $this->criador_id = 	filter_var($this->criador_id, FILTER_SANITIZE_NUMBER_INT);
            $this->capa = 	htmlspecialchars($this->capa, ENT_QUOTES, "UTF-8");
            $this->data_criacao = 	htmlspecialchars($this->data_criacao, ENT_QUOTES, "UTF-8");
            $this->latitude = 	htmlspecialchars($this->latitude, ENT_QUOTES, "UTF-8");
            $this->longitude = 	htmlspecialchars($this->longitude, ENT_QUOTES, "UTF-8");
            $this->data_evento = 	htmlspecialchars($this->data_evento, ENT_QUOTES, "UTF-8");
    }
    
    /**
    * Evento pertence a Usuario
    * @return Usuario $Usuario
    */
    function getUsuario() {
        return $this->belongsTo('Usuario','criador_id');
    }
}