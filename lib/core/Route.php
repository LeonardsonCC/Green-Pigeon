<?php

class Route {

    private static $route = array();

    /**
     * Define controler e action com base em um rota   
     * @author Samue S Gonçalves <samuuel.gs@gmail.com>
     * <b>Exemplo: </b> Route::set('contato', 'index', 'contato');<br>
     * Quando acessada a aplicação/contato, o controler a ser
     * acessado é o IndexController é p método é o contato.
     * 
     * @param string $rota Nome da rota
     * @param string $controller Controler responsavel pela rora
     * @param string $action Método do controler responsável pela view e model
     */
    public static function set($rota, $controller, $action = 'index') {
        $rota = strtolower($rota);
        self::$route[$rota] = array($controller, $action);
    }

    public static function checkRoute($controller) {
        $rota = strtolower($controller);
        if (array_key_exists($rota, self::$route)) {
            return self::$route[$rota];
        } else {
            return FALSE;
        }
    }

}
