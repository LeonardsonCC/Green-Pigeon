<?php

/**
 * Description of Email
 * Classe para envio de e-mails de forma estática.
 * @author Healfull
 * 
 */
class Email {

    protected static $email;

    /**
     * Realiza as configurações básicas do SMTP
     * 
     * @param String $charset default UTF-8
     */
    protected static function configSmtp($charset = 'UTF-8') {
        require_once("lib/util/phpmailer/PHPMailerAutoload.php");
        self::$email = new PHPMailer();
        self::$email->IsSMTP();
        self::$email->SMTPSecure = Config::get('systemMailSMTPSecure');
        self::$email->Host = Config::get('systemMailHost');
        self::$email->SMTPDebug = 0;
        self::$email->SMTPAuth = true;
        self::$email->Port = Config::get('systemMailPort');
        self::$email->CharSet = $charset;
    }

    /**
     * Configura o e-mail usado para envios
     * Tem como valores padrão os dados informados na config.php
     * 
     * @param String $email default NULL
     * @param String $password default NULL
     */
    protected static function setUserMail($email = null, $password = null) {
        self::$email->Username = !$email ? Config::get('systemMail') : $email;
        self::$email->Password = !$password ? Config::get('systemMailPass') : $password;
    }

    /**
     * Envio de e-mail de sistema
     * Utliza as informações da config.php
     * 
     * @param String|Array $to - destinatário ou array de destinatários
     * @param String $subject assunto
     * @param String $msg corpo do e-mail
     * @return boolean
     */
    public static function sendMail($to, $subject, $msg) {

        self::configSmtp();
        self::setUserMail();

        self::$email->sender = Config::get('systemMailReply');
        self::$email->SetFrom(Config::get('systemMailReply'), Config::get('systemMailName'), false);
        self::$email->addReplyTo(Config::get('systemMailReply'), Config::get('systemMailName'));
        self::$email->Subject = $subject;
        self::$email->MsgHTML($msg);
        if (is_array($to)) {
            foreach ($to as $t) {
                self::$email->AddAddress($t);
            }
        } else {
            self::$email->AddAddress($to);
        }

        return self::$email->Send();
    }

    /**
     * Envio de e-mail com remetente (reply) diferente
     * 
     * Reply para o usuário remetente
     * 
     * @param String|Array $to - destinatário ou array de destinatários
     * @param String $subject assunto
     * @param String $msg corpo do e-mail
     * @param String $sender remetente
     * @return boolean
     */
    public static function sendMailFromSender($to, $subject, $msg, $sender) {

        self::configSmtp();
        self::setUserMail();

        self::$email->SetFrom($sender, Config::get('systemMailName') . " - " . $sender);
        self::$email->addReplyTo($sender, Config::get('systemMailName') . " - " . $sender);

        self::$email->Subject = $subject;
        self::$email->MsgHTML($msg);
        if (is_array($to)) {
            foreach ($to as $t) {
                self::$email->AddAddress($t);
            }
        } else {
            self::$email->AddAddress($to);
        }

        return self::$email->Send();
    }

}
