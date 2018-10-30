<?php

final class LoginController extends AppController {
    # Nome do Model que representa a tabela que guarda os dados dos usuários:

    private $model = 'Usuario';
    # Nome do campo da tabela que armazena o LOGIN:
    private $login = 'email';
    # Nome do campo da tabela que armazena a SENHA:
    private $password = 'senha';
    # Nome do campo da tabela que armazena o E-MAIL:
    private $email = 'email';

    function index() {
        $this->go('Login', 'login');
    }

    function login() {    
        $user = Session::get('user');
        if (Session::get('user') && ($user instanceof Usuario)) {
            $this->go(Config::get('indexController'), Config::get('indexAction'));          
        }
        $this->setTitle('Entrar - Green Pigeon');
        $this->set('user', $this->model);
    }

    function post_login() {
        $this->setTitle('Login');
        $c = new Criteria();
        $c->addCondition($this->login, '=', $_POST['login']);
        $c->addCondition($this->password, '=', md5(Config::get('salt') . $_POST['password']));
        $model = $this->model;
        $this->set('user', $this->model);
        $user = $model::getFirst($c);
        if ($user) {
            new Msg('Bem vindo ' . $user->nome);
            Session::set('user', $user);
            $this->go(Config::get('indexController'), Config::get('indexAction')); # Ao autenticar, redireciona para...
        } else {
            new Msg('Login ou senha incorretos. Por favor, tente novamente.', 3);
        }
    }

    function logout() {
        Session::set('user', NULL);     
        $this->goUrl(SITE_PATH);
    }

    function send() {
        $this->setTitle('Recuperar senha');
    }

    function post_send() {
        $this->setTitle('Recuperar senha');
        $c = new Criteria();
        $c->addCondition($this->email, '=', $_POST['email']);
        $model = $this->model;
        $user = $model::getFirst($c);
        if ($user) {
            $agora = new DateTime();
            $agora->add(new DateInterval('PT10M')); # 10 minutos de validade do email
            # email:
            $subject = "Recuperação de senha em " . $_SERVER['HTTP_HOST'];
            $message = "Olá,<p>Alguém (provavelmente você) pediu para mudar a senha da sua conta em ";
            $message .= $_SERVER['HTTP_HOST'] . ".</p>";
            $message .= "<p>Para confirmar este pedido e cadastrar uma nova senha, vá ao seguinte endereço web: ";
            $message .= "<a href='" . $_SERVER['HTTP_HOST'] . "/" . SITE_PATH . "/index.php?s=" . $sigla . "&m=Login&p=reset&recuperar=" . Cript::cript(Config::get('salt') . $user->{$model::PK}) . "&d=" . urlencode(Cript::cript($agora->format('Y-m-d H:i'))) . "'>Gerar uma nova senha</a></p>";
            $message .= '<p>Ou copie este endereço e cole no seu navegador:</p>';
            $message .= '<p>http://' . $_SERVER['HTTP_HOST'] . "/" . SITE_PATH . "/index.php?s=" . $sigla . "&m=Login&p=reset&recuperar=" . Cript::cript(Config::get('salt') . $user->{$model::PK}) . "&d=" . urlencode(Cript::cript($agora->format('Y-m-d H:i'))) . '</p>';

            if (Email::sendMail($_POST['email'], $subject, $message)) {
                new Msg('Um e-mail foi enviado para ' . $_POST['email'] . ' com as instruções. <br>Caso não tenha recebido, aguarde dois minutos, verifique sua caixa de SPAM ou tente novamente.');
            } else {
                new Msg('Não foi possível recuperar a senha para este e-mail. Tente novamente após alguns minutos.', 3);
            }
        } else {
            new Msg('E-mail não encontrado! Tente novamente.', 3);
        }
        $this->goUrl(SITE_PATH . '/' . $sigla);
    }

    function reset() {
        $id = (int) str_replace(Config::get('salt'), '', Cript::decript($_GET['recuperar']));
        $sigla = $_GET['s'];
        try {
            $agora = new DateTime();
            $dataURL = str_replace(Config::get('salt'), '', urldecode(Cript::decript($_GET['d'])));
            $antes = new DateTime($dataURL);
            #meia hora de validade do email
            if ($agora > $antes) {
                throw new Exception('<p>Este link expirou. Solicite novamente sua senha.</p>');
                $this->go('Login', 'login');
            }
            $model = $this->model;
            $user = new $model($id);
            $senha = $this->gerarSenha();
            $user->{$this->password} = md5(Config::get('salt') . $senha);
            $user->save();

            #envia email com a nova senha
            $subject = "Nova senha em " . $_SERVER['HTTP_HOST'];
            $message = "Olá de novo,<p>você pediu para mudar a senha da sua conta em ";
            $message .= $_SERVER['HTTP_HOST'] . ".</p>";
            $message .= "<p>Sua nova senha é: <strong>$senha</strong></p>";
            #Email::sendMail($user->{$this->email}, $subject, $message);
            if (Email::sendMail($user->{$this->email}, $subject, $message)) {
                new Msg('Uma nova senha foi gerada e enviada para o seu email! <br> Não esqueça de verificar sua caixa de SPAM.');
            } else {
                new Msg('Não foi possível enviar a senha para este e-mail. Tente novamente após alguns minutos.', 3);
            }
            $this->goUrl(SITE_PATH . '/' . $sigla);
        } catch (Exception $exc) {
            new Msg($exc->getMessage(), 3);
            $this->goUrl(SITE_PATH . '/' . $sigla);
        }
    }

    public function gerarSenha($length = 8) {
        $salt = "abcdefghijklmnpqrstuvwxyz123456789";
        $len = strlen($salt);
        $pass = '';
        mt_srand(10000000 * (double) microtime());
        for ($i = 0; $i < $length; $i++) {
            $pass .= $salt[mt_rand(0, $len - 1)];
        }
        return $pass;
    }

}
