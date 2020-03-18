<?php

/**
 * Classe ACL 
 * Esta classe implementa uma forma simples de controle de acesso de URLs
 * Ative a funcionalidade no arquivo /config.php
 */
class Acl {
    # bloqueia todas URLs não descritas no atributo $acl

    private static $denyByDefault = true;

    # Redireciona para a URL abaixo quando acesso não autorizado
    public static $redirectTo = '/Login';

    # nome da sessão a ser buscada para definir o papel 'logged'
    public static $loggedSession = 'user';

    # Define explicitamente o acesso de acordo com os papeis:
    #   public: qualquer usuário
    #   logged: Sessão ativa (usuário logado)
    # Uso: 
    # 'papel' => array( 'controller' => array( 'metodo1' => true, 'metodo2'=> false, [...] ) );
    private static $acl = array(
        # páginas públicas
        'public' => array(
            'Index' => array(
                'index' => true
            ),
            'Login' => array(
                'login' => true
            ),
            'Usuario' => array(
                'cadastrar' => true,
                'ver' => true
            ),
            'Ponto_coleta' => array(
                'mapa' => true
            ),
            'Evento' => array(
                'lista' => true,
                'ver' => true
            ),
            'Dica' => array(
                'dicas' => true
            ),
            '' => array(
                '' => true
            )
        ),
        1 => array(
            'Login' => array(
                'logout' => true
            ),
            'Ponto_coleta' => array(
                'cadastrar' => true
            ),
            'Voto' => array(
                'cadastrar' => true,
                'votar' => true
            ),
            'Usuario' => array(
                'ranking' => true,
                'editar' => true,
                'apagar' => true
            )
        ),
        2 => array(
            'Login' => array(
                '*' => true
            ),
            'Voto' => array(
                '*' => true
            ),
            'Ponto_coleta' => array(
                '*' => true
            ),
            'Index' => array(
                '*' => true
            ),
            'Usuario' => array(
                '*' => true
            ),
            'Dica' => array(
                '*' => true
            ),
            'Evento' => array(
                '*' => true
            ),
            'Categoria' => array(
                '*' => true
            ),
            'Feedback' => array(
                '*' => true
            )
        )
    );

    /**
     * Verifica se possui acesso a URL informada ($controller/$action)
     * 
     * Verifica se o usuário está ou não logado de acordo com a sessão definida 
     * no atributo $loggedSession e com as especificações contidas no atributo Acl::$acl
     * 
     * Esta classe é facilmente ampliável para gerenciar múltiplos níveis de acesso.
     * Para isto, basta atribuir na variável $role do método check o papel do usuário.
     * Exemplo - Se $user for um objeto do modelo Usuario com um atributo (int) nivel:
     * Altere a linha 104 para 
     *  $role = $user->nivel;
     * 
     * Altere o atributo Acl::$acl conforme exemplo abaixo:
     * # acesso para usuários nivel 1
     * '1' => array(
     * '*' => array(
     * 'cadastrar' => true,
     * 'editar' => true,
     * 'apagar' => true
     * )
     * ),
     * # acesso para usuários nivel 2
     * '2' => array(
     * '*' => array(
     * 'editar' => true,
     * 'apagar' => true
     * )
     * )
     * 
     * @param String $controller 
     * @param String $action
     * @param Object $user opcional
     * @return boolean 
     */
    public static function check($controller, $action, $user = NULL) {
        if (!Config::get('enableACL')) {
            return true;
        }
        # Verifica se existe rota
        if (empty($action)) {
            $route = Route::checkRoute($controller);
            if (is_array($route)) {
                $controller = $route[0];
                $action = $route[1];
            }
        }
        if($user){ 
            $role = $user->adm;
        }
        else {
            $role = NULL;
        }
        $allow = !self::$denyByDefault;
        if (isset(self::$acl['public']['*'][$action])) {
            $allow = self::$acl['public']['*'][$action];
        }
        if (isset(self::$acl['public'][$controller]['*'])) {
            $allow = self::$acl['public'][$controller]['*'];
        }
        if (isset(self::$acl['public'][$controller][$action])) {
            $allow = self::$acl['public'][$controller][$action];
        }
        if ($user && isset(self::$acl[$role]['*'][$action])) {
            $allow = self::$acl[$role]['*'][$action];
        }
        if ($user && isset(self::$acl[$role][$controller]['*'])) {
            $allow = self::$acl[$role][$controller]['*'];
        }
        if ($user && isset(self::$acl[$role][$controller][$action])) {
            $allow = self::$acl[$role][$controller][$action];
        }
        return $allow;
    }

}
