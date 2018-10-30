<?php

/**
* classe Feedback
*
* @author Instalador LazyPHP <http://lazyphp.com.br>
* @version 16/10/2018 19:30
*/
final class Feedback extends Record{ 

    const TABLE = 'feedback';
    const PK = 'id';
    
    public $id;
    public $texto;
    public $email;
    public $receber_email;
    
    /**
    * Configurações e filtros globais do modelo
    * @return Criteria $criteria
    */
    public static function configure(){
        $criteria = new Criteria();
        $criteria->paginate(20, 'paginaFeedback');
        return $criteria;
    }
    
    /**
    * Sanitize - filtra os caracteres válidos para cada atributo
    * Configure corretamente por questão de segurança (XSS)
    * Este método é chamado automaticamente pelo método save() da superclasse
    */
    public function sanitize(){
            $this->id = 	filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);
            $this->texto = 	$this->htmlFilter($this->texto); # vide /lib/core/Record.php
            $this->email = 	htmlspecialchars($this->email, ENT_QUOTES, "UTF-8");
            $this->receber_email = 	$this->htmlFilter($this->receber_email); # vide /lib/core/Record.php
    }
}