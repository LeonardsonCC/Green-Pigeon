<?php

/**
* classe Dica
*
* @author Instalador LazyPHP <http://lazyphp.com.br>
* @version 06/09/2018 21:38
*/
final class Dica extends Record{ 

    const TABLE = 'dica';
    const PK = 'id';
    
    public $id;
    public $titulo;
    public $texto;
    public $criador_id;
    public $data_criacao;
    public $categoria_id;
    
    /**
    * Configurações e filtros globais do modelo
    * @return Criteria $criteria
    */
    public static function configure(){
        $criteria = new Criteria();
        $criteria->paginate(10, 'paginaDica');
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
            $this->data_criacao = 	htmlspecialchars($this->data_criacao, ENT_QUOTES, "UTF-8");
            $this->categoria_id = 	filter_var($this->categoria_id, FILTER_SANITIZE_NUMBER_INT);
    }
    
    /**
    * Dica pertence a Categoria
    * @return Categoria $Categoria
    */
    function getCategoria() {
        return $this->belongsTo('Categoria','categoria_id');
    }
    
    /**
    * Dica pertence a Usuario
    * @return Usuario $Usuario
    */
    function getUsuario() {
        return $this->belongsTo('Usuario','criador_id');
    }
}