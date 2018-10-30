<?php

/**
* classe Categoria
*
* @author Instalador LazyPHP <http://lazyphp.com.br>
* @version 06/09/2018 21:38
*/
final class Categoria extends Record{ 

    const TABLE = 'categoria';
    const PK = 'id';
    
    public $id;
    public $nome;
    public $link;
    
    /**
    * Configurações e filtros globais do modelo
    * @return Criteria $criteria
    */
    public static function configure(){
        $criteria = new Criteria();
        $criteria->paginate(20, 'paginaCategoria');
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
            $this->link = 	htmlspecialchars($this->link, ENT_QUOTES, "UTF-8");
    }
    
    /**
    * Categoria possui Dicas
    * @return Dica[] array de Dicas
    */
    function getDicas( $criteria = NULL ) {
        return $this->hasMany('Dica','categoria_id',$criteria);
    }
    
    /**
    * Categoria possui Usuarios via Dica (NxN)
    * @return Usuario[] array de Usuarios
    */
    function getDicaUsuarios( $criteria = NULL ) {
        return $this->hasNN('Dica','categoria_id','criador_id','Usuario',$criteria);
    }
    
    /**
    * Categoria possui Ponto_coletas
    * @return Ponto_coleta[] array de Ponto_coletas
    */
    function getPonto_coletas( $criteria = NULL ) {
        return $this->hasMany('Ponto_coleta','categoria_id',$criteria);
    }
    
    /**
    * Categoria possui Usuarios via Ponto_coleta (NxN)
    * @return Usuario[] array de Usuarios
    */
    function getPonto_coletaUsuarios( $criteria = NULL ) {
        return $this->hasNN('Ponto_coleta','categoria_id','usuario_id','Usuario',$criteria);
    }
}