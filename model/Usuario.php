<?php

/**
* classe Usuario
*
* @author Instalador LazyPHP <http://lazyphp.com.br>
* @version 06/09/2018 21:38
*/
final class Usuario extends Record{ 

    const TABLE = 'usuario';
    const PK = 'id';
    
    public $id;
    public $email;
    public $senha;
    public $nome;
    public $avatar;
    public $adm;
    public $pontuacao;
    
    /**
    * Configurações e filtros globais do modelo
    * @return Criteria $criteria
    */
    public static function configure(){
        $criteria = new Criteria();
        $criteria->paginate(20, 'paginaUsuario');
        return $criteria;
    }
    
    /**
    * Sanitize - filtra os caracteres válidos para cada atributo
    * Configure corretamente por questão de segurança (XSS)
    * Este método é chamado automaticamente pelo método save() da superclasse
    */
    public function sanitize(){
            $this->id = 	filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);
            $this->email = 	filter_var($this->email, FILTER_SANITIZE_EMAIL);
            $this->senha = 	$this->senha;
            $this->nome = 	htmlspecialchars($this->nome, ENT_QUOTES, "UTF-8");
            $this->avatar = 	htmlspecialchars($this->avatar, ENT_QUOTES, "UTF-8");
            $this->adm = 	filter_var($this->adm, FILTER_SANITIZE_NUMBER_INT);
            $this->pontuacao = 	filter_var($this->pontuacao, FILTER_SANITIZE_NUMBER_INT);
    }
    
    /**
    * Usuario possui Dicas
    * @return Dica[] array de Dicas
    */
    function getDicas( $criteria = NULL ) {
        return $this->hasMany('Dica','criador_id',$criteria);
    }
    
    /**
    * Usuario possui Categorias via Dica (NxN)
    * @return Categoria[] array de Categorias
    */
    function getDicaCategorias( $criteria = NULL ) {
        return $this->hasNN('Dica','criador_id','categoria_id','Categoria',$criteria);
    }
    
    /**
    * Usuario possui Ponto_coletas
    * @return Ponto_coleta[] array de Ponto_coletas
    */
    function getPonto_coletas( $criteria = NULL ) {
        return $this->hasMany('Ponto_coleta','usuario_id',$criteria);
    }
    
    /**
    * Usuario possui Categorias via Ponto_coleta (NxN)
    * @return Categoria[] array de Categorias
    */
    function getPonto_coletaCategorias( $criteria = NULL ) {
        return $this->hasNN('Ponto_coleta','usuario_id','categoria_id','Categoria',$criteria);
    }
}