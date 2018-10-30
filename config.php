<?php
date_default_timezone_set("America/Sao_Paulo");
header('Content-Type:text/html; charset=UTF-8');
##################################
# CONFIGURAÇÕES DO SGBD
# MySQL
##################################

# servidor do SGBD:
Config::set('db_host', 'localhost');
# usuario do SGBD:
Config::set('db_user', 'root');
# senha do SGBD:
Config::set('db_password', '');
# nome do banco de dados:
Config::set('db_name', 'green_pigeon');


##################################
# CONFIGURAÇÕES GERAIS
##################################

# MODO DE DEPURAÇÃO
# desenvolvimento: true
# produção: false
Config::set('debug', false);

# TEMPLATE PADRÃO
# Ex: para o diretório /template/default:
# Config::set('template', 'default');
Config::set('template', 'default');


# Chave da aplicação, para controle de sessões e criptografia
# Utilize uma cadeia alfanumérica aleatória única
Config::set('key', '6KsQyyhIMYDILeUwvf5Z');

# SALT - Utilizada na criptografia
# Utiliza uma chave alfa-numéria complexa de no mínimo 16 dígitos
Config::set('salt', '2XjoObDgZQHHr946grOu');

# internacionalização
Config::set('lang', 'pt_br');
#urls amigaveis (recomendado true)
Config::set('rewriteURL', true);

# Página inicial - Controller
Config::set('indexController', 'Index');
#Página inicial - Método do controller
Config::set('indexAction', 'index');
# parâmetros get criptografados
Config::set('criptedGetParamns', array());

##################################
# ACL - Access Control List (opcional)
# 
# O framework passará a utilizar a classe /lib/util/Acl.php
# para gerenciar o controle de acesso de páginas e exibição de links
# de acordo com a especificação do atributo Acl::$acl
##################################

Config::set('enableACL', true);

##################################
# EMAIL (opcional) 
# 
# Configura o sender para envio de emails, recuperação de senha, etc
# Dica: se for usar gmail, não esqueça de ativar o IMAP da sua conta
# Dica: ative o open_ssl no seu php.ini
##################################

# email para envio (deve existir)
Config::set('systemMail', 'foo@bar.com.br');
# senha do email
Config::set('systemMailPass', '');
# responder para...
Config::set('systemMailReply', 'foo@bar.com.br');
# endereço do servidor SMTP
Config::set('systemMailHost', 'mail.bar.com.br');
# nome que aparecerá no email
Config::set('systemMailName', $_SERVER['HTTP_HOST']);
# porta utilizada pelo serviço SMTP (padrão: 587)
Config::set('systemMailPort', 587);
# Tipo de encriptação utilizada pela conexão SMTP - opções: '', 'ssl' ou 'tls' 
Config::set('systemMailSMTPSecure', '');