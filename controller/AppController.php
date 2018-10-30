<?php

/**
 * Classe AppController
 * 
 * @author Miguel
 * @package \controller
 */
class AppController extends Controller {
    # extensões permitidas pela classe util/FileUploader

    public $extensoes = array(
        'pdf', 'jpg', 'png', 'gif', 'zip', 'rar', 'doc', 'docx', 'ppt', 'pps',
        'pptx', 'txt', 'xls', 'xlsx', 'odt', 'ods', 'odp', 'mp3', 'mp4',
        'wmv', 'rmvb', 'avi'
    );

    /**
     * Esta função é automaticamente executada antes da execução da funçao do
     * Controller especificado.
     * 
     * Implemente aqui regras globais que podem valer para todos os Controllers
     */
    public function beforeRun() {
        
        # segurança da página quando ativado o ACL no config
        if (!Acl::check(CONTROLLER, ACTION, Session::get(Acl::$loggedSession))) {
            $this->goUrl(SITE_PATH . Acl::$redirectTo);
        }
        # preserva as ordenações
        if ($this->getParam('ordenaPor')) {
            Session::set(CONTROLLER . ACTION . APPKEY . '.ordenaPor', $this->getParam('ordenaPor'));
        }
        $or = Session::get(CONTROLLER . ACTION . APPKEY . '.ordenaPor');
        if (!empty($or) && !($this->getParam('ordenaPor'))) {
            $this->setParam('ordenaPor', Session::get(CONTROLLER . ACTION . APPKEY . '.ordenaPor'));
        }
    }

    

    function tempoAtras($time) {
        if (!$time) {
            return 'nunca';
        }
        $parts = array(
            array(31104000, 'ano', 'anos'),
            array(2592000, 'mês', 'meses'),
            array(86400, 'dia', 'dias'),
            array(3600, 'hora', 'horas'),
            array(60, 'minuto', 'minutos'),
            array(1, 'segundo', 'segundos'),
        );

        $relativeTo = new DateTime($time);
        $DT = new DateTime();
        $diff = $DT->format('U') - $relativeTo->format('U');
        $past = TRUE;
        if ($diff < 0) {
            $past = FALSE;
            $diff *= -1;
        }
        foreach ($parts as $subparts) {
            $n = floor($diff / $subparts[0]);
            if ($n) {
                $output = '%d %s';
                $part = $subparts[$n > 1 ? 2 : 1];
                if ($past) {
                    $part .= ' atrás';
                }
                return sprintf($output, $n, $part);
            }
        }
        return 'agora';
    }

    public function printDebug($mixvar) {
        echo '<pre>';
        print_r($mixvar);
        echo '</pre>';
    }

    function preparaUrl($string) {
        $acentos = array(
            'À', 'Á', 'Ã', 'Â', 'à', 'á', 'ã', 'â',
            'Ê', 'É',
            'Í', 'í',
            'Ó', 'Õ', 'Ô', 'ó', 'õ', 'ô',
            'Ú', 'Ü',
            'Ç', 'ç',
            'é', 'ê',
            'ú', 'ü', ' '
        );
        $remove_acentos = array(
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e',
            'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u',
            'c', 'c',
            'e', 'e',
            'u', 'u', '_'
        );
        return urldecode(str_replace($acentos, $remove_acentos, $string));
    }


}
