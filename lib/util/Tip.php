<?php

/**
 * Description of Tip
 *
 * @author Miguel
 */
class Tip {

    /**
     * Constrói e retorna um box com informações
     * 
     * @param String $msg
     */
    function __construct($msg) {
        $_SESSION['frameworkTip' . APPKEY][] = $msg;
    }

    /**
     * Busca as mensagens do sistema.<br>
     * <b>deve ser utilizado apenas no template.</b><br>
     * 
     * Exemplo: <?php echo $this->getMsg();?>
     * 
     * 
     * @return String mensagem
     */
    public static function getTips() {
        if (isset($_SESSION['frameworkTip' . APPKEY])) {
            $tip = '<div class="tip panel panel-warning"><div class="panel-heading">';
            foreach ($_SESSION['frameworkTip' . APPKEY] as $value) {
                $tip .= '<p>';
                $tip .= '<i class="fa fa-lightbulb-o" aria-hidden="true"></i> ';
                $tip .= $value;
                $tip .= '</p>';
            }
            $tip.= '</div></div><br>';
            unset($_SESSION['frameworkTip' . APPKEY]);
            return $tip;
        }
    }

}
