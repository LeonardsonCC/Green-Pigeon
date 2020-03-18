<?php

class Msg {

    /**
     * Mostra uma mensagem na próxima renderização de uma view
     * 
     * @param String $msg
     * @param int $tipo {1,2,3,4}  1=Success, 2=Warning, 3=Error, 4=info
     */
    function __construct($msg, $tipo = 1) {
        switch ($tipo) {
            case 1:
                $_SESSION['frameworkMsg' . APPKEY][] = $this->getTemplate('green lighten-2', 'check', $msg);
                break;
            case 2:
                $_SESSION['frameworkMsg' . APPKEY][] = $this->getTemplate('yellow lighten-2', 'warning', $msg);
                break;
            case 3:
                $_SESSION['frameworkMsg' . APPKEY][] = $this->getTemplate('red', 'close', $msg);
                break;
            case 4:
                $_SESSION['frameworkMsg' . APPKEY][] = $this->getTemplate('blue lighten-3', 'info', $msg);
                break;
            default:
                $_SESSION['frameworkMsg' . APPKEY][] = $this->getTemplate('green lighten-2', 'check', $msg);
        }
    }

    private function getTemplate($class, $icon, $msg) {
        
        $template = '<script>';
        $template .= 'M.toast({html: \'';
        $template .= '<i class="material-icons" style="margin-right: 20px;">'.$icon.'</i> <span>'.$msg.'</span>\'';
        $template .= ', classes: \''.$class.'\'})';
        $template .= '</script>';
        
        return $template;
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
    public static function getMsg() {
        if (isset($_SESSION['frameworkMsg' . APPKEY])) {
            $msgarr = $_SESSION['frameworkMsg' . APPKEY];
            unset($_SESSION['frameworkMsg' . APPKEY]);
            $msg = '';
            foreach ($msgarr as $value) {
                $msg .= $value;
            }
            return $msg;
        }
        return null;
    }

}
