<?php

/**
* classe Ponto_coleta
*
* @author Instalador LazyPHP <http://lazyphp.com.br>
* @version 06/09/2018 21:38
*/
final class Ponto_coleta extends Record{ 

    const TABLE = 'ponto_coleta';
    const PK = 'id';
    
    public $id;
    public $nome;
    public $latitude;
    public $longitude;
    public $descricao;
    public $exibir;
    public $aprovacoes;
    public $desaprovacoes;
    public $data_criacao;
    public $usuario_id;
    public $categoria_id;
    
    /**
    * Configurações e filtros globais do modelo
    * @return Criteria $criteria
    */
    public static function configure(){
        $criteria = new Criteria();
        $criteria->paginate(20, 'paginaPonto_coleta');
        return $criteria;
    }
    
    /**
    * Sanitize - filtra os caracteres válidos para cada atributo
    * Configure corretamente por questão de segurança (XSS)
    * Este método é chamado automaticamente pelo método save() da superclasse
    */
    public function sanitize(){
            $this->id = 	filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);
            $this->nome = 	htmlspecialchars($this->nome, ENT_QUOTES, "UTF-8");
            $this->latitude = 	filter_var($this->latitude, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
            $this->longitude = 	filter_var($this->longitude, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
            $this->descricao = 	$this->htmlFilter($this->descricao); # vide /lib/core/Record.php
            $this->exibir = 	filter_var($this->exibir, FILTER_SANITIZE_NUMBER_INT);
            $this->aprovacoes = 	filter_var($this->aprovacoes, FILTER_SANITIZE_NUMBER_INT);
            $this->desaprovacoes = 	filter_var($this->desaprovacoes, FILTER_SANITIZE_NUMBER_INT);
            $this->data_criacao = 	htmlspecialchars($this->data_criacao, ENT_QUOTES, "UTF-8");
            $this->usuario_id = 	filter_var($this->usuario_id, FILTER_SANITIZE_NUMBER_INT);
            $this->categoria_id = 	filter_var($this->categoria_id, FILTER_SANITIZE_NUMBER_INT);
    }
    
    /**
    * Ponto_coleta pertence a Categoria
    * @return Categoria $Categoria
    */
    function getCategoria() {
        return $this->belongsTo('Categoria','categoria_id');
    }
    
    /**
    * Ponto_coleta pertence a Usuario
    * @return Usuario $Usuario
    */
    function getUsuario() {
        return $this->belongsTo('Usuario','usuario_id');
    }
}