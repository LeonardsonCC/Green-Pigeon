<?php

class InstallController extends AppController {

    private $dbschema = NULL;
    private $tableSchema = array();

    public function __construct() {
       $this->go('Seu', 'Safadinho');
    }

    public function index() {
        $this->setTitle('Instalador LazyPHP');
        $ok = true;
        if (!$this->checkDB()) {
            $ok = false;
        }
        if (!is_writable('model')) {
            new Msg('O diretório<strong> /model </strong>não tem permissão de escrita', 3);
            $ok = false;
        }
        if (!is_writable('controller')) {
            new Msg('O diretório<strong> /controller </strong>não tem permissão de escrita', 3);
            $ok = false;
        }
        if (!is_writable('view')) {
            new Msg('O diretório<strong> /view </strong>não tem permissão de escrita', 3);
            $ok = false;
        }
        if (!is_writable('template')) {
            new Msg('O diretório<strong> /template </strong>não tem permissão de escrita', 3);
            $ok = false;
        }
        if ($ok) {
            $this->set('tables', $this->getTables());
            $this->set('ok', TRUE);
            $this->set('dbschema', $this->getDbSchema());
        } else {
            $this->set('tables', array());
            $this->set('ok', FALSE);
            $this->set('dbschema', array());
        }
    }

    public function post_index() {
        $tables = $this->getTables();
        $this->set('tables', $tables);
        $overwrite = filter_input(INPUT_POST, 'sobrescrever', FILTER_VALIDATE_BOOLEAN);
        $modelos = (array) filter_input(INPUT_POST, 'model', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $controles = (array) filter_input(INPUT_POST, 'controller', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $visoes = (array) filter_input(INPUT_POST, 'view', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach ($modelos as $modelo => $valor) {
            $this->installM($modelo, $overwrite);
        }
        foreach ($controles as $controle => $valor) {
            $this->installC($controle, $overwrite);
        }
        foreach ($visoes as $table => $valor) {
            // index
            if (!is_dir('view/' . ucfirst($table))) {
                mkdir('view/' . ucfirst($table));
            }
            if (file_exists('view/' . ucfirst($table) . '/index.php') && !$overwrite) {
                new Msg(__('View %s index.php ignorado. O arquivo já existe.', ucfirst($table)), 2);
            } else {
                $handle = fopen("view/" . ucfirst($table) . "/index.php", 'w');
                $this->installViewIndex($table, $handle);
                fclose($handle);
            }
            // ver
            if (file_exists('view/' . ucfirst($table) . '/ver.php') && !$overwrite) {
                new Msg(__('View %s ver.php ignorado. O arquivo já existe.', ucfirst($table)), 2);
            } else {
                $handle = fopen("view/" . ucfirst($table) . "/ver.php", 'w');
                $this->installViewView($table, $handle);
                fclose($handle);
            }
            // lista
            if (file_exists('view/' . ucfirst($table) . '/lista.php') && !$overwrite) {
                new Msg(__('View %s lista.php ignorado. O arquivo já existe.', ucfirst($table)), 2);
            } else {
                $handle = fopen("view/" . ucfirst($table) . "/lista.php", 'w');
                $this->installViewAll($table, $handle);
                fclose($handle);
            }
            //cadastrar
            if (file_exists('view/' . ucfirst($table) . '/cadastrar.php') && !$overwrite) {
                new Msg(__('View %s cadastrar.php ignorado. O arquivo já existe.', ucfirst($table)), 2);
            } else {
                $handle = fopen("view/" . ucfirst($table) . "/cadastrar.php", 'w');
                $this->installViewAdd($table, $handle);
                fclose($handle);
            }
            //editar
            if (file_exists('view/' . ucfirst($table) . '/editar.php') && !$overwrite) {
                new Msg(__('View %s editar.php ignorado. O arquivo já existe.', ucfirst($table)), 2);
            } else {
                $handle = fopen("view/" . ucfirst($table) . "/editar.php", 'w');
                $this->installViewEdit($table, $handle);
                fclose($handle);
            }
            //apagar
            if (file_exists('view/' . ucfirst($table) . '/apagar.php') && !$overwrite) {
                new Msg(__('View %s apagar.php ignorado. O arquivo já existe.', ucfirst($table)), 2);
            } else {
                $handle = fopen("view/" . ucfirst($table) . "/apagar.php", 'w');
                $this->installViewDelete($table, $handle);
                fclose($handle);
            }
        }
        if (filter_input(INPUT_POST, 'menu')) {
            $this->installMenu();
        }
        $this->go('Install', '');
    }

    private function installM($table, $overwrite = false) {
        if (file_exists('model/' . ucfirst($table) . '.php') && !$overwrite) {
            new Msg(__('Model %s.php ignorado. O arquivo já existe.', ucfirst($table)), 2);
            return;
        }

        $used = array('1');
        $dbschema = $this->getDbSchema();
        $tableschema = $this->getTableSchema($table);
        $handle = fopen("model/" . ucfirst($table) . ".php", 'w');
        if (!$handle) {
            new Msg(__('Não foi possível criar o model %s. Verifique as permissões do diretório', ucfirst($table)), 3);
            return;
        }
        fwrite($handle, "<?php\n");
        fwrite($handle, $this->nlt(0) . "/**");
        fwrite($handle, $this->nlt(0) . "* classe " . ucfirst($table));
        fwrite($handle, $this->nlt(0) . "*");
        fwrite($handle, $this->nlt(0) . "* @author Instalador LazyPHP <http://lazyphp.com.br>");
        fwrite($handle, $this->nlt(0) . "* @version " . date("d/m/Y H:i"));
        fwrite($handle, $this->nlt(0) . "*/");
        //fwrite($handle, "namespace model;\n\n");
        fwrite($handle, $this->nlt(0) . "final class " . ucfirst($table) . " extends Record{ \n");
        fwrite($handle, $this->nlt(1) . 'const TABLE = \'' . $table . '\';');
        fwrite($handle, $this->nlt(1) . 'const PK = \'');
        foreach ($tableschema as $field) {
            if ($field->Key == 'PRI') {
                fwrite($handle, $field->Field);
                break;
            }
        }
        fwrite($handle, '\';');

        # atributos do modelo
        fwrite($handle, $this->nlt(1));
        foreach ($tableschema as $field) {
            fwrite($handle, $this->nlt(1) . 'public $' . $field->Field . ';');
        }
        # configure
        fwrite($handle, $this->nlt(1));
        fwrite($handle, $this->nlt(1) . '/**');
        fwrite($handle, $this->nlt(1) . '* Configurações e filtros globais do modelo');
        fwrite($handle, $this->nlt(1) . '* @return Criteria $criteria');
        fwrite($handle, $this->nlt(1) . '*/');
        fwrite($handle, $this->nlt(1) . 'public static function configure(){');
        fwrite($handle, $this->nlt(2) . '$criteria = new Criteria();');
        fwrite($handle, $this->nlt(2) . '$criteria->paginate(20, \'pagina' . ucfirst($table) . '\');');
        fwrite($handle, $this->nlt(2) . 'return $criteria;');
        fwrite($handle, $this->nlt(1) . '}');
        # sanitize
        fwrite($handle, $this->nlt(1));
        fwrite($handle, $this->nlt(1) . '/**');
        fwrite($handle, $this->nlt(1) . '* Sanitize - filtra os caracteres válidos para cada atributo');
        fwrite($handle, $this->nlt(1) . '* Configure corretamente por questão de segurança (XSS)');
        fwrite($handle, $this->nlt(1) . '* Este método é chamado automaticamente pelo método save() da superclasse');
        fwrite($handle, $this->nlt(1) . '*/');
        fwrite($handle, $this->nlt(1) . 'public function sanitize(){');
        $schema = $this->getTableSchema($table);
        foreach ($schema as $property) {
            $tipo = filter_input(INPUT_POST, 'tipo_' . $table . '_' . $property->Field);
            fwrite($handle, $this->nlt(3) . '$this->' . $property->Field . ' = ' . "\t");
            switch ($tipo) {
                case 'text':
                case 'date':
                case 'datetime':
                case 'time':
                case 'textarea':
                case 'image':
                case 'file':
                    fwrite($handle, 'htmlspecialchars($this->' . $property->Field . ', ENT_QUOTES, "UTF-8");');
                    break;
                case 'number':
                case 'selectFK':
                case 'radioFK':
                case 'checkbox':
                    fwrite($handle, 'filter_var($this->' . $property->Field . ', FILTER_SANITIZE_NUMBER_INT);');
                    break;
                case 'now':
                    fwrite($handle, 'date("Y-m-d H:i:s");');
                    break;
                case 'password':
                    fwrite($handle, '$this->' . $property->Field . ';');
                    break;
                case 'decimal':
                    fwrite($handle, 'filter_var($this->' . $property->Field . ', FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);');
                    break;
                case 'email':
                    fwrite($handle, 'filter_var($this->' . $property->Field . ', FILTER_SANITIZE_EMAIL);');
                    break;
                case 'textarea_html':
                    fwrite($handle, '$this->htmlFilter($this->' . $property->Field . '); # vide /lib/core/Record.php');
                    break;
                default:
                    fwrite($handle, 'htmlspecialchars($this->' . $property->Field . ', ENT_QUOTES, "UTF-8");');
                    break;
            }
        }
        fwrite($handle, $this->nlt(1) . '}');

        foreach ($dbschema as $v) {
            if ($v->table == $table) {
                $mname = 'get' . ucfirst(($v->reftable));
                if (array_search($mname, $used)) {
                    $mname .= '_'.$v->fk; 
                }
                $used[] = $mname;
                fwrite($handle, $this->nlt(1));
                fwrite($handle, $this->nlt(1) . '/**');
                fwrite($handle, $this->nlt(1) . '* ' . ucfirst($table) . ' pertence a ' . ucfirst($v->reftable));
                fwrite($handle, $this->nlt(1) . '* @return ' . ucfirst($v->reftable) . ' $' . ucfirst($v->reftable));
                fwrite($handle, $this->nlt(1) . '*/');
                fwrite($handle, $this->nlt(1) . 'function ' . $mname . '() {');
                fwrite($handle, $this->nlt(2) . 'return $this->belongsTo(\'' . ucfirst($v->reftable) . '\',\'' . $v->fk . '\');');
                fwrite($handle, $this->nlt(1) . "}");
            }
            if ($v->reftable == $table) {
                $mname = 'get' . $this->getPlural(ucfirst(($v->table)));
                if (array_search($mname, $used)) {
                    $mname .= '_'.$v->fk; 
                }
                $used[] = $mname;
                fwrite($handle, $this->nlt(1));
                fwrite($handle, $this->nlt(1) . '/**');
                fwrite($handle, $this->nlt(1) . '* ' . ucfirst($table) . ' possui ' . $this->getPlural(ucfirst($v->table)));
                fwrite($handle, $this->nlt(1) . '* @return ' . ucfirst($v->table) . '[] array de ' . $this->getPlural(ucfirst($v->table)));
                fwrite($handle, $this->nlt(1) . '*/');
                fwrite($handle, $this->nlt(1) . 'function ' . $mname . '( $criteria = NULL ) {');
                fwrite($handle, $this->nlt(2) . 'return $this->hasMany(\'' . ucfirst(($v->table)) . '\',\'' . $v->fk . '\',$criteria);');
                fwrite($handle, $this->nlt(1) . "}");

                # hasNN
                $NNschema = $this->getDbSchema();
                foreach ($NNschema as $schema) {
                    if ($schema->table == $v->table) {
                        # auto relacionamento??
                        # se for, TO DO
                        if ($table == $schema->reftable) {
                            continue;
                        }

                        $mname = 'get' . ucfirst($schema->table) . $this->getPlural(ucfirst(($schema->reftable)));
                        if (array_search($mname, $used)) {
                            $mname .= '_'.$v->fk; 
                        }
                        $used[] = $mname;
                        fwrite($handle, $this->nlt(1));
                        fwrite($handle, $this->nlt(1) . '/**');
                        fwrite($handle, $this->nlt(1) . '* ' . ucfirst($table) . ' possui ' . $this->getPlural(ucfirst($schema->reftable)) . ' via ' . ucfirst($schema->table) . ' (NxN)');
                        fwrite($handle, $this->nlt(1) . '* @return ' . ucfirst($schema->reftable) . '[] array de ' . $this->getPlural(ucfirst($schema->reftable)));
                        fwrite($handle, $this->nlt(1) . '*/');
                        fwrite($handle, $this->nlt(1) . 'function ' . $mname . '( $criteria = NULL ) {');
                        fwrite($handle, $this->nlt(2) . 'return $this->hasNN(\'' . ucfirst(($v->table)) . '\',\'' . $v->fk . '\',\'' . $schema->fk . '\',\'' . ucfirst($schema->reftable) . '\',$criteria);');
                        fwrite($handle, $this->nlt(1) . "}");
                    }
                }
            }
        }
        fwrite($handle, $this->nlt(0) . "}");
        fclose($handle);
    }

    private function installC($table, $overwrite = false) {
        if (file_exists('controller/' . ucfirst($table) . 'Controller.php') && !$overwrite) {
            new Msg(__('Controller %sController.php ignorado. O arquivo já existe.', ucfirst($table)), 2);
            return;
        }
        $dbschema = $this->getDbSchema($table);
        $tableschema = $this->getTableSchema($table);
        $priField = $tableschema[0]->Field;
        foreach ($tableschema as $f) {
            if ($f->Type == 'PRI') {
                $priField = $f->Field;
                break;
            }
        }
        $handle = fopen("controller/" . ucfirst($table) . "Controller.php", 'w');
        if (!$handle) {
            new Msg(__('Não foi possível criar o controller %s. Verifique as permissões do diretório', ucfirst($table)), 3);
            return;
        }
        fwrite($handle, "<?php\n");
        fwrite($handle, $this->nlt(0) . "/**");
        fwrite($handle, $this->nlt(0) . "* classe " . ucfirst($table) . 'Controller');
        fwrite($handle, $this->nlt(0) . "*");
        fwrite($handle, $this->nlt(0) . "* @author Instalador LazyPHP <http://lazyphp.com.br>");
        fwrite($handle, $this->nlt(0) . "* @version " . date("d/m/Y H:i"));
        fwrite($handle, $this->nlt(0) . "*/");
        fwrite($handle, $this->nlt(0) . "final class " . ucfirst($table) . "Controller extends AppController{ \n");
        # index
        fwrite($handle, $this->nlt(1) . '# página inicial do módulo ' . ucfirst($table));
        fwrite($handle, $this->nlt(1) . "function inicio(){");
        fwrite($handle, $this->nlt(2) . '$this->setTitle(\'' . ucfirst($table) . '\');');
        fwrite($handle, $this->nlt(1) . "}\n");
        # lista
        fwrite($handle, $this->nlt(1) . '# lista de ' . $this->getPlural(ucfirst($table)));
        fwrite($handle, $this->nlt(1) . '# renderiza a visão /view/' . ucfirst($table) . '/lista.php');
        fwrite($handle, $this->nlt(1) . 'function lista(){');
        fwrite($handle, $this->nlt(2) . '$this->setTitle(\'' . $this->getPlural(ucfirst($table)) . '\');');
        fwrite($handle, $this->nlt(2) . '$c = new Criteria();');
        fwrite($handle, $this->nlt(2) . 'if ( $this->getParam(\'pesquisa\') ) {');
        #pesquisa
        $searchString = filter_input(INPUT_POST, 'pesquisa_' . $table);
        fwrite($handle, $this->nlt(3) . '$c->addCondition(\'' . $searchString . '\', \'LIKE\', \'%\' . $this->getParam(\'pesquisa\') . \'%\');');
        fwrite($handle, $this->nlt(2) . '}');
        fwrite($handle, $this->nlt(2) . 'if ($this->getParam(\'ordenaPor\')) {');
        fwrite($handle, $this->nlt(3) . '$c->setOrder($this->getParam(\'ordenaPor\'));');
        fwrite($handle, $this->nlt(2) . '}');
        fwrite($handle, $this->nlt(2) . '$this->set(\'' . $this->getPlural(ucfirst($table)) . '\', ' . ucfirst($table) . '::getList($c));');
        fwrite($handle, $this->nlt(1) . "}\n");
        # ver
        fwrite($handle, $this->nlt(1) . '# visualiza um(a) ' . ucfirst($table));
        fwrite($handle, $this->nlt(1) . '# renderiza a visão /ver/' . ucfirst($table) . '/ver.php');
        fwrite($handle, $this->nlt(1) . 'function ver(){');
        fwrite($handle, $this->nlt(2) . 'try {');
        fwrite($handle, $this->nlt(3) . '$' . ucfirst($table) . ' = new ' . ucfirst($table) . '( (int)$this->getParam(0) );');
        fwrite($handle, $this->nlt(3) . '$this->set(\'' . ucfirst($table) . '\', $' . ucfirst($table) . ');');
        $listas = (array) filter_input(INPUT_POST, 'colunasVerLista_' . $table, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach ($listas as $value) {
            $partes = explode('__', $value);
            $atributo = $partes[1];
            fwrite($handle, $this->nlt(3) . '$this->set(\'' . $atributo . '\',$' . ucfirst($table) . '->get' . $atributo . '());');
        }

        $atributos = (array) filter_input(INPUT_POST, 'colunasVer_' . $table, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach ($tableschema as $field) {
            foreach ($atributos as $atributo) {
                if ($atributo == $field->Field) {
                    fwrite($handle, $this->nlt(3) . '$this->setTitle($' . ucfirst($table) . '->' . $field->Field . ');');
                    break 2;
                }
            }
        }
        fwrite($handle, $this->nlt(2) . '} catch (Exception $e) {');
        fwrite($handle, $this->nlt(3) . 'new Msg($e->getMessage(), 2);');
        fwrite($handle, $this->nlt(3) . '$this->go(\'' . ucfirst($table) . '\', \'lista\');');
        fwrite($handle, $this->nlt(2) . '}');
        fwrite($handle, $this->nlt(1) . "}\n");
        # cadastrar 
        fwrite($handle, $this->nlt(1) . '# formulário de cadastro de ' . ucfirst($table));
        fwrite($handle, $this->nlt(1) . '# renderiza a visão /view/' . ucfirst($table) . '/cadastrar.php');
        fwrite($handle, $this->nlt(1) . 'function cadastrar(){');
        fwrite($handle, $this->nlt(2) . '$this->setTitle(\'Cadastrar ' . (ucfirst($table)) . '\');');
        fwrite($handle, $this->nlt(2) . '$this->set(\'' . ucfirst($table) . '\', new ' . ucfirst($table) . ');');
        foreach ($dbschema as $v) {
            if ($v->table == $table) {
                fwrite($handle, $this->nlt(2) . '$this->set(\'' . $this->getPlural(ucfirst(($v->reftable))) . '\',  ' . ucfirst(($v->reftable)) . '::getList());');
            }
        }
        fwrite($handle, $this->nlt(1) . "}\n");
        # post_cadastrar
        fwrite($handle, $this->nlt(1) . '# recebe os dados enviados via post do cadastro de ' . ucfirst($table));
        fwrite($handle, $this->nlt(1) . '# (true)redireciona ou (false) renderiza a visão /view/' . ucfirst($table) . '/cadastrar.php');
        fwrite($handle, $this->nlt(1) . 'function post_cadastrar(){');
        fwrite($handle, $this->nlt(2) . '$this->setTitle(\'Cadastrar ' . (ucfirst($table)) . '\');');
        fwrite($handle, $this->nlt(2) . '$' . ucfirst($table) . ' = new ' . ucfirst($table) . '();');
        fwrite($handle, $this->nlt(2) . '$this->set(\'' . ucfirst($table) . '\', $' . ucfirst($table) . ');');
        fwrite($handle, $this->nlt(2) . 'try {');
        $schema = $this->getTableSchema($table);
        foreach ($schema as $property) {
            $tipo = filter_input(INPUT_POST, 'tipo_' . $table . '_' . $property->Field);
            switch ($tipo) {
                case 'now':
                    fwrite($handle, $this->nlt(3) . '$' . ucfirst($table) . '->' . $property->Field . ' = ');
                    fwrite($handle, 'date("Y-m-d H:i:s");');
                    break;
                case 'password':
                    fwrite($handle, $this->nlt(3) . '$' . ucfirst($table) . '->' . $property->Field . ' = ');
                    fwrite($handle, 'md5(Config::get(\'salt\') . filter_input(INPUT_POST , \''
                            . $property->Field . '\'));');
                    break;
                case 'checkbox':
                    fwrite($handle, $this->nlt(3) . '$' . ucfirst($table) . '->' . $property->Field . ' = ');
                    fwrite($handle, '(bool)filter_input(INPUT_POST , \'' . $property->Field . '\' );');
                    break;
                case 'image':
                    fwrite($handle, $this->nlt(3) . 'if (!empty($_FILES[\'' . $property->Field . '\'][\'name\'])){');
                    fwrite($handle, $this->nlt(4) . '$imagem = $_FILES[\'' . $property->Field . '\'];');
                    fwrite($handle, $this->nlt(4) . '$IU = new ImageUploader($imagem, 1024); # max 1024px de largura');
                    fwrite($handle, $this->nlt(4) . '# salva com nome original em /uploads/images');
                    fwrite($handle, $this->nlt(4) . '$' . ucfirst($table) . '->' . $property->Field . ' = ');
                    fwrite($handle, '$IU->save($imagem[\'name\'], \'images\');');
                    fwrite($handle, $this->nlt(3) . '}');
                    break;
                case 'file':
                    fwrite($handle, $this->nlt(3) . 'if (!empty($_FILES[\'' . $property->Field . '\'][\'name\'])){');
                    fwrite($handle, $this->nlt(4) . '$arquivo = $_FILES[\'' . $property->Field . '\'];');
                    fwrite($handle, $this->nlt(4) . '# vide atributo $this->extensoes em AppController');
                    fwrite($handle, $this->nlt(4) . '$FU = new FileUploader($arquivo, NULL, $this->extensoes);');
                    fwrite($handle, $this->nlt(4) . '# salva com nome original em /uploads/arquivos');
                    fwrite($handle, $this->nlt(4) . '$' . ucfirst($table) . '->' . $property->Field . ' = ');
                    fwrite($handle, '$FU->save($arquivo[\'name\'], \'arquivos\');');
                    fwrite($handle, $this->nlt(3) . '}');
                    break;
                default:
                    fwrite($handle, $this->nlt(3) . '$' . ucfirst($table) . '->' . $property->Field . ' = ');
                    fwrite($handle, 'filter_input(INPUT_POST , \'' . $property->Field . '\');');
                    break;
            }
        }
        fwrite($handle, $this->nlt(3) . '$' . ucfirst($table) . '->save();');
        fwrite($handle, $this->nlt(3) . 'new Msg(\'' . ucfirst($table) . ' salvo(a)!\');');
        fwrite($handle, $this->nlt(2) . '} catch (Exception $e) {');
        fwrite($handle, $this->nlt(3) . 'new Msg($e->getMessage(),3);');
        fwrite($handle, $this->nlt(2) . '}');
        fwrite($handle, $this->nlt(2) . 'if(filter_input(INPUT_POST , \'url_origem\')){');
        fwrite($handle, $this->nlt(3) . '$this->goUrl(Cript::decript(filter_input(INPUT_POST , \'url_origem\')));');
        fwrite($handle, $this->nlt(2) . '}');        
        fwrite($handle, $this->nlt(2) . '$this->go(\'' . ucfirst($table) . '\', \'lista\');');
        foreach ($dbschema as $v) {
            if ($v->table == $table) {
                fwrite($handle, $this->nlt(2) . '$this->set(\'' . $this->getPlural(ucfirst(($v->reftable))) . '\',  ' . ucfirst(($v->reftable)) . '::getList());');
            }
        }
        fwrite($handle, $this->nlt(1) . "}\n");
        # editar
        fwrite($handle, $this->nlt(1) . '# formulário de edição de ' . ucfirst($table));
        fwrite($handle, $this->nlt(1) . '# renderiza a visão /view/' . ucfirst($table) . '/editar.php');
        fwrite($handle, $this->nlt(1) . 'function editar(){');
        fwrite($handle, $this->nlt(2) . '$this->setTitle(\'Editar ' . (ucfirst($table)) . '\');');
        fwrite($handle, $this->nlt(2) . 'try {');
        fwrite($handle, $this->nlt(3) . '$this->set(\'' . ucfirst($table) . '\', new ' . ucfirst($table) . '((int) $this->getParam(0)));');
        foreach ($dbschema as $v) {
            if ($v->table == $table) {
                fwrite($handle, $this->nlt(3) . '$this->set(\'' . $this->getPlural(ucfirst(($v->reftable))) . '\',  ' . ucfirst(($v->reftable)) . '::getList());');
            }
        }
        fwrite($handle, $this->nlt(2) . '} catch (Exception $e) {');
        fwrite($handle, $this->nlt(3) . 'new Msg($e->getMessage(),3);');
        fwrite($handle, $this->nlt(3) . '$this->go(\'' . ucfirst($table) . '\', \'lista\');');
        fwrite($handle, $this->nlt(2) . '}');
        fwrite($handle, $this->nlt(1) . "}\n");
        # post_editar
        fwrite($handle, $this->nlt(1) . '# recebe os dados enviados via post da edição de ' . ucfirst($table));
        fwrite($handle, $this->nlt(1) . '# (true)redireciona ou (false) renderiza a visão /view/' . ucfirst($table) . '/editar.php');
        fwrite($handle, $this->nlt(1) . 'function post_editar(){');
        fwrite($handle, $this->nlt(2) . '$this->setTitle(\'Editar ' . (ucfirst($table)) . '\');');
        fwrite($handle, $this->nlt(2) . 'try {');
        fwrite($handle, $this->nlt(3) . '$' . ucfirst($table) . ' = new ' . ucfirst($table) . '( (int)$this->getParam(0) );');
        fwrite($handle, $this->nlt(3) . '$this->set(\'' . ucfirst($table) . '\', $' . ucfirst($table) . ');');
        foreach ($schema as $property) {
            if ($property->Key == 'PRI') {
                continue;
            }
            $tipo = filter_input(INPUT_POST, 'tipo_' . $table . '_' . $property->Field);
            switch ($tipo) {
                case 'now':
                    fwrite($handle, $this->nlt(3) . '# atualizar data?');
                    fwrite($handle, $this->nlt(3) . '# $' . ucfirst($table) . '->' . $property->Field . ' = ');
                    fwrite($handle, 'date("Y-m-d H:i:s");');
                    break;
                case 'password':
                    fwrite($handle, $this->nlt(3) . '$password = filter_input(INPUT_POST , \''
                            . $property->Field . '\');');
                    fwrite($handle, $this->nlt(3) . 'if(!empty($password)){');
                    fwrite($handle, $this->nlt(4) . '$' . ucfirst($table) . '->' . $property->Field . ' = ');
                    fwrite($handle, 'md5(Config::get(\'salt\') . $password);');
                    fwrite($handle, $this->nlt(3) . '}');
                    break;
                case 'checkbox':
                    fwrite($handle, $this->nlt(3) . '$' . ucfirst($table) . '->' . $property->Field . ' = ');
                    fwrite($handle, '(bool)filter_input(INPUT_POST , \'' . $property->Field . '\' );');
                    break;
                case 'image':
                    fwrite($handle, $this->nlt(3) . 'if (!empty($_FILES[\'' . $property->Field . '\'][\'name\'])){');
                    fwrite($handle, $this->nlt(4) . '$imagem = $_FILES[\'' . $property->Field . '\'];');
                    fwrite($handle, $this->nlt(4) . '$IU = new ImageUploader($imagem, 1024); # max 1024px de largura');
                    fwrite($handle, $this->nlt(4) . '# salva com nome original em /uploads/images');
                    fwrite($handle, $this->nlt(4) . '$' . ucfirst($table) . '->' . $property->Field . ' = ');
                    fwrite($handle, '$IU->save($imagem[\'name\'], \'images\');');
                    fwrite($handle, $this->nlt(3) . '}');
                    break;
                case 'file':
                    fwrite($handle, $this->nlt(3) . 'if (!empty($_FILES[\'' . $property->Field . '\'][\'name\'])){');
                    fwrite($handle, $this->nlt(4) . '$arquivo = $_FILES[\'' . $property->Field . '\'];');
                    fwrite($handle, $this->nlt(4) . '# vide atributo $this->extensoes em AppController');
                    fwrite($handle, $this->nlt(4) . '$FU = new FileUploader($arquivo, NULL, $this->extensoes);');
                    fwrite($handle, $this->nlt(4) . '# salva com nome original em /uploads/arquivos');
                    fwrite($handle, $this->nlt(4) . '$' . ucfirst($table) . '->' . $property->Field . ' = ');
                    fwrite($handle, '$FU->save($arquivo[\'name\'], \'arquivos\');');
                    fwrite($handle, $this->nlt(3) . '}');
                    break;
                default:
                    fwrite($handle, $this->nlt(3) . '$' . ucfirst($table) . '->' . $property->Field . ' = ');
                    fwrite($handle, 'filter_input(INPUT_POST , \'' . $property->Field . '\');');
                    break;
            }
        }
        fwrite($handle, $this->nlt(3) . '$' . ucfirst($table) . '->save();');
        fwrite($handle, $this->nlt(3) . 'new Msg(\'Atualização concluída!\');');
        fwrite($handle, $this->nlt(2) . '} catch (Exception $e) {');
        fwrite($handle, $this->nlt(3) . 'new Msg(\'A atualização não foi concluída! \'.$e->getMessage(),3);');
        fwrite($handle, $this->nlt(2) . '}');
        fwrite($handle, $this->nlt(2) . 'if(filter_input(INPUT_POST , \'url_origem\')){');
        fwrite($handle, $this->nlt(3) . '$this->goUrl(Cript::decript(filter_input(INPUT_POST , \'url_origem\')));');
        fwrite($handle, $this->nlt(2) . '}');        
        fwrite($handle, $this->nlt(2) . '$this->go(\'' . ucfirst($table) . '\', \'lista\');');
        foreach ($dbschema as $v) {
            if ($v->table == $table) {
                fwrite($handle, $this->nlt(2) . '$this->set(\'' . $this->getPlural(ucfirst(($v->reftable))) . '\',  ' . ucfirst(($v->reftable)) . '::getList());');
            }
        }
        fwrite($handle, $this->nlt(1) . "}\n");
        # apagar
        fwrite($handle, $this->nlt(1) . '# Confirma a exclusão ou não de um(a) ' . ucfirst($table));
        fwrite($handle, $this->nlt(1) . '# renderiza a /view/' . ucfirst($table) . '/apagar.php');
        fwrite($handle, $this->nlt(1) . 'function apagar(){');
        fwrite($handle, $this->nlt(2) . '$this->setTitle(\'Apagar ' . (ucfirst($table)) . '\');');
        fwrite($handle, $this->nlt(2) . 'try {');
        fwrite($handle, $this->nlt(3) . '$this->set(\'' . ucfirst($table) . '\', new ' . ucfirst($table) . '((int)$this->getParam(0)));');
        fwrite($handle, $this->nlt(2) . '} catch (Exception $e) {');
        fwrite($handle, $this->nlt(3) . 'new Msg($e->getMessage(), 2);');
        fwrite($handle, $this->nlt(3) . '$this->go(\'' . ucfirst($table) . '\', \'lista\');');
        fwrite($handle, $this->nlt(2) . '}');
        fwrite($handle, $this->nlt(1) . "}\n");
        # post_apagar
        fwrite($handle, $this->nlt(1) . '# Recebe o id via post e exclui um(a) ' . ucfirst($table));
        fwrite($handle, $this->nlt(1) . '# redireciona para ' . ucfirst($table) . '/lista');
        fwrite($handle, $this->nlt(1) . 'function post_apagar(){');
        fwrite($handle, $this->nlt(2) . 'try {');
        fwrite($handle, $this->nlt(3) . '$' . ucfirst($table) . ' = new ' . ucfirst($table) . '((int) filter_input(INPUT_POST , \'id\'));');
        fwrite($handle, $this->nlt(3) . '$' . ucfirst($table) . '->delete();');
        fwrite($handle, $this->nlt(3) . 'new Msg(\'' . ucfirst($table) . ' excluído(a)!\', 1);');
        fwrite($handle, $this->nlt(2) . '} catch (Exception $e) {');
        fwrite($handle, $this->nlt(3) . 'new Msg($e->getMessage(),3);');
        fwrite($handle, $this->nlt(2) . '}');
        fwrite($handle, $this->nlt(2) . 'if(filter_input(INPUT_POST , \'url_origem\')){');
        fwrite($handle, $this->nlt(3) . '$this->goUrl(Cript::decript(filter_input(INPUT_POST , \'url_origem\')));');
        fwrite($handle, $this->nlt(2) . '}');     
        fwrite($handle, $this->nlt(2) . '$this->go(\'' . ucfirst($table) . '\', \'lista\');');
        fwrite($handle, $this->nlt(1) . "}\n");

        fwrite($handle, $this->nlt(0) . "}");
        fclose($handle);
    }

    private function installViewIndex($table, $handle) {
        fwrite($handle, '<?php');
        fwrite($handle, $this->nlt(0) . '# Visão view/' . ucfirst($table) . '/index.php ');
        fwrite($handle, $this->nlt(0) . '/* @var $this ' . ucfirst($table) . 'Controller */');
        fwrite($handle, $this->nlt(0) . '?>');
        fwrite($handle, '<h1>' . ucfirst($table) . '</h1>');
    }

    private function installViewView($table, $handle) {
        $dbschema = $this->getDbSchema($table);
        $tableschema = $this->getTableSchema($table);
        fwrite($handle, '<?php');
        fwrite($handle, $this->nlt(0) . '# Visão view/' . ucfirst($table) . '/ver.php ');
        fwrite($handle, $this->nlt(0) . '/* @var $this ' . ucfirst($table) . 'Controller */');
        fwrite($handle, $this->nlt(0) . '/* @var $' . ucfirst($table) . ' ' . ucfirst($table) . ' */');
        fwrite($handle, $this->nlt(0) . '?>');
        fwrite($handle, $this->nlt(0) . '<div class="ver ' . $table . ' panel panel-default">');
        fwrite($handle, $this->nlt(0) . '<div class="panel-body">');
        $atributos = (array) filter_input(INPUT_POST, 'colunasVer_' . $table, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $fields = array();
        foreach ($tableschema as $field) {
            foreach ($atributos as $atributo) {
                if ($atributo == $field->Field) {
                    $fields[] = $field;
                }
            }
        }
        $first = array_shift($fields);
        
        if (count($fields)) {
            fwrite($handle, $this->nlt(1) . '<div class="panel panel-info">');
            fwrite($handle, $this->nlt(2) . '<div class="panel-heading">');
            fwrite($handle, $this->nlt(3) . '<h1><?php echo $' . ucfirst($table) . '->' . ($first->Field) . ';?></h1>');
            fwrite($handle, $this->nlt(2) . '</div>');
            fwrite($handle, $this->nlt(2) . '<div class="panel-body">');
        }
        foreach ($fields as $field) {
            $used = array('1');
            foreach ($dbschema as $v) {
                if ($v->table == $table && $v->fk == $field->Field) {
                    $belongsSchema = $this->getTableSchema($v->reftable);
                    $bField = filter_input(INPUT_POST, 'colunasVerTitleRef_' . $table . '_' . $field->Field);
                    $priRefField = $belongsSchema[0]->Field;
                    foreach ($belongsSchema as $bf) {
                        if ($bf->Type == 'PRI') {
                            $priRefField = $bf->Field;
                            break;
                        }
                    }
                    $mname = 'get' . ucfirst(($v->reftable));
                    $cused = 2;
                    while (array_search($mname, $used)) {
                        $mname .= $cused;
                    }
                    $used[] = $mname;
                    fwrite($handle, $this->nlt(3) . '<div class="atributo col-sm-6">');
                    fwrite($handle, $this->nlt(4) . '<div class="col-md-3"><strong>' . ucfirst(($v->reftable)) . '</strong>: </div>');
                    fwrite($handle, $this->nlt(4) . '<div class="col-md-9">');
                    fwrite($handle, $this->nlt(5) . '<?php');
                    fwrite($handle, $this->nlt(5) . 'echo $this->Html->getLink($' . ucfirst($table) . '->' . $mname . '()->' . $bField . ', \'' . ucfirst(($v->reftable)) . '\', \'ver\',');
                    fwrite($handle, $this->nlt(6) . 'array($' . ucfirst($table) . '->' . $mname . '()->' . $priRefField . '), // variaveis via GET opcionais');
                    fwrite($handle, $this->nlt(6) . 'array(\'class\' => \'\')); // atributos HTML opcionais');
                    fwrite($handle, $this->nlt(4) . '?>');
                    fwrite($handle, $this->nlt(4) . '</div>');
                    fwrite($handle, $this->nlt(3) . '</div>');
                    continue 2;
                }
            }

            $tipo = filter_input(INPUT_POST, 'tipo_' . $table . '_' . $field->Field);
            $nome = filter_input(INPUT_POST, 'form_' . $table . '_' . $field->Field);

            if ($tipo == 'textarea' || $tipo == 'textarea_html') {
                fwrite($handle, $this->nlt(3) . '<div class="atributo ' . $field->Field . ' col-sm-12">');
                fwrite($handle, $this->nlt(4) . '<div class="name"><strong>' . $nome . '</strong>: </div>');
                fwrite($handle, $this->nlt(4) . '<div class="value"><?php echo $' . ucfirst($table) . '->' . ($field->Field) . ';?></div>');
                fwrite($handle, $this->nlt(3) . '</div>');
            } elseif ($tipo == 'image') {
                fwrite($handle, $this->nlt(3) . '<div class="atributo ' . $field->Field . ' col-sm-12">');
                fwrite($handle, $this->nlt(4) . '<div class="name col-md-3"><strong>' . $nome . '</strong>: </div>');
                fwrite($handle, $this->nlt(4) . '<div class="value col-md-9"><img src="<?php echo SITE_PATH.\'/\'.$' . ucfirst($table) . '->' . ($field->Field) . ';?>" style="max-width:100%" onerror="$(this).hide();"></div>');
                fwrite($handle, $this->nlt(3) . '</div>');
            } else {
                fwrite($handle, $this->nlt(3) . '<div class="atributo ' . $field->Field . ' col-sm-6">');
                fwrite($handle, $this->nlt(4) . '<div class="name col-md-3"><strong>' . $nome . '</strong>: </div>');
                if ($tipo == 'now' || $tipo == 'datetime') {
                    fwrite($handle, $this->nlt(4) . '<?php $horario = new DateTime($' . ucfirst($table) . '->' . ($field->Field) . '); ?>');
                    fwrite($handle, $this->nlt(4) . '<div class="value col-md-9"><?php echo date_format($horario, \'H:i d/m/Y\');?></div>');
                } elseif ($tipo == 'date') {
                    fwrite($handle, $this->nlt(4) . '<?php $data = new DateTime($' . ucfirst($table) . '->' . ($field->Field) . '); ?>');
                    fwrite($handle, $this->nlt(4) . '<div class="value col-md-9"><?php echo date_format($data, \'d/m/Y\');?></div>');
                } elseif ($tipo == 'time') {
                    fwrite($handle, $this->nlt(4) . '<?php $horario = new DateTime($' . ucfirst($table) . '->' . ($field->Field) . '); ?>');
                    fwrite($handle, $this->nlt(4) . '<div class="value col-md-9"><?php echo date_format($horario, \'H:i:s\');?></div>');
                } elseif ($tipo == 'password') {
                    fwrite($handle, $this->nlt(4) . '<div class="value col-md-9">**********</div>');
                } elseif ($tipo == 'file') {
                    fwrite($handle, $this->nlt(4) . '<div class="value"><a target="_blank" href="<?php echo SITE_PATH.\'/\'.$' . ucfirst($table) . '->' . ($field->Field) . ';?>"><?php echo $' . ucfirst($table) . '->' . ($field->Field) . ';?></a></div>');
                } elseif ($tipo == 'checkbox') {
                    fwrite($handle, $this->nlt(4) . '<?php');
                    fwrite($handle, $this->nlt(4) . '$check = \'<i class="fa fa-times"></i>\';');
                    fwrite($handle, $this->nlt(4) . 'if($' . ucfirst($table) . '->' . ($field->Field) . '){');
                    fwrite($handle, $this->nlt(5) . '$check = \'<i class="fa fa-check"></i>\';');
                    fwrite($handle, $this->nlt(4) . '}');
                    fwrite($handle, $this->nlt(4) . '?>');
                    fwrite($handle, $this->nlt(4) . '<div class="value col-md-9"><?php echo $check;?></div>');
                } else {
                    fwrite($handle, $this->nlt(4) . '<div class="value col-md-9"><?php echo $' . ucfirst($table) . '->' . ($field->Field) . ';?></div>');
                }
                fwrite($handle, $this->nlt(3) . '</div>');
            }
        }
        if (count($fields)) {
            fwrite($handle, $this->nlt(3) . '<div class="clearfix"></div>');
            fwrite($handle, $this->nlt(2) . '</div>');
            fwrite($handle, $this->nlt(1) . '</div>');
        }
        fwrite($handle, $this->nlt(1) . '<br>');
        fwrite($handle, $this->nlt(1) . "\n");
        fwrite($handle, $this->nlt(1));
        $listas = (array) filter_input(INPUT_POST, 'colunasVerLista_' . $table, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach ($listas as $value) {
            $partes = explode('__', $value);
            $tabela = $partes[0];
            $atributo = $partes[1];
            fwrite($handle, $this->nlt(1) . '<div class="text-right">');
            fwrite($handle, $this->nlt(2) . '<?php');
            fwrite($handle, $this->nlt(2) . 'echo $this->Html->getModalLink(');
            fwrite($handle, $this->nlt(3) . '\'<i class="fa fa-plus-circle"></i> Cadastrar ' . $tabela . '\', ');
            fwrite($handle, $this->nlt(3) . '\'' . ucfirst($tabela) . '\', \'cadastrar\', ');
            if (count($partes) > 2) {
                $fk = $partes[2];
                $pk = $partes[3];
                fwrite($handle, $this->nlt(3) . 'array(\'' . $fk . '\' => $' . ucfirst($table) . '->' . $pk . ', \'url_origem\' => Cript::cript($this->getCurrentURL())),');
            } else {
                fwrite($handle, $this->nlt(3) . 'array(\'url_origem\' => Cript::cript($this->getCurrentURL())),');
            }
            fwrite($handle, $this->nlt(3) . 'array(\'class\' => \'btn btn-default\')');
            fwrite($handle, $this->nlt(3) . ');');
            fwrite($handle, $this->nlt(2) . '?>');
            fwrite($handle, $this->nlt(1) . '</div>');
            $this->installViewViewAll($tabela, $atributo, $handle);
        }

        fwrite($handle, $this->nlt(0) . '</div>');
        fwrite($handle, $this->nlt(0) . '</div>');
        fwrite($handle, $this->nlt(0) . '<!-- LazyPHP.com.br -->');
    }

    # listar de ver

    private function installViewViewAll($table, $var, $handle) {
        $dbschema = $this->getDbSchema($table);
        $tableschema = $this->getTableSchema($table);
        $priField = $tableschema[0]->Field;
        foreach ($tableschema as $f) {
            if ($f->Key == 'PRI') {
                $priField = $f->Field;
                break;
            }
        }

        fwrite($handle, '<?php');
        fwrite($handle, $this->nlt(0) . '/* @var $' . $var . ' ' . ucfirst($table) . '[] */');
        fwrite($handle, $this->nlt(0) . '?>');
        fwrite($handle, $this->nlt(1) . '<div class="lista ' . ucfirst($table) . ' panel panel-default">');
        fwrite($handle, $this->nlt(2) . '<div class="panel-heading"><h2 class="panel-title">' . $this->getPlural(ucfirst($table)) . '</h2></div>');
        fwrite($handle, $this->nlt(2) . '<div class="panel-body">');
        fwrite($handle, $this->nlt(2) . '<!-- tabela de resultados de ' . $this->getPlural(ucfirst($table)) . ' -->');
        fwrite($handle, $this->nlt(2) . '<div class="clearfix">  ');
        fwrite($handle, $this->nlt(3) . '<div class="table-responsive">');
        fwrite($handle, $this->nlt(4) . '<table class="table table-hover">');
        fwrite($handle, $this->nlt(5) . '<thead>');
        fwrite($handle, $this->nlt(5) . '<tr>');
        $atributos = (array) filter_input(INPUT_POST, 'colunasAll_' . $table, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach ($tableschema as $f) {
            $usar = false;
            foreach ($atributos as $atributo) {
                if ($atributo == $f->Field) {
                    $usar = true;
                    break;
                }
            }
            if (!$usar) {
                continue;
            }
            fwrite($handle, $this->nlt(6) . '<th>' . filter_input(INPUT_POST, 'form_' . $table . '_' . $f->Field) . '</th>');
        }
        fwrite($handle, $this->nlt(5) . '</tr>');
        fwrite($handle, $this->nlt(5) . '</thead>');

        fwrite($handle, $this->nlt(5) . '<?php');
        fwrite($handle, $this->nlt(5) . 'foreach ($' . $var . ' as $' . substr(strtolower($table), 0, 1) . ') {');
        fwrite($handle, $this->nlt(6) . 'echo \'<tr>\';');
        foreach ($tableschema as $f) {
            $usar = false;
            foreach ($atributos as $atributo) {
                if ($atributo == $f->Field) {
                    $usar = true;
                    break;
                }
            }
            if (!$usar) {
                continue;
            }
            $used = array('1');
            foreach ($dbschema as $dbs) {
                if ($dbs->table == $table && $dbs->fk == $f->Field) {
                    $reftableschema = $this->getTableSchema($dbs->reftable);
                    $priRefField = $reftableschema[0]->Field;
                    foreach ($reftableschema as $fref) {
                        if ($fref->Key == 'PRI') {
                            $priRefField = $fref->Field;
                            break;
                        }
                    }
                    $mname = 'get' . ucfirst(($dbs->reftable));
                    $cused = 2;
                    while (array_search($mname, $used)) {
                        $mname .= $cused;
                    }
                    $used[] = $mname;
                    fwrite($handle, $this->nlt(6) . 'echo \'<td>\';');
                    fwrite($handle, $this->nlt(6) . 'echo $this->Html->getLink($' . substr(strtolower($table), 0, 1) . '->' . $mname . '()->' . filter_input(INPUT_POST, 'colunasTitleRef_' . $table . '_' . $f->Field) . ', \'' . ucfirst(($dbs->reftable)) . '\', \'ver\',');
                    fwrite($handle, $this->nlt(7) . 'array($' . substr(strtolower($table), 0, 1) . '->' . $mname . '()->' . $priRefField . '), // variaveis via GET opcionais');
                    fwrite($handle, $this->nlt(7) . 'array()); // atributos HTML opcionais');
                    fwrite($handle, $this->nlt(6) . 'echo \'</td>\';');
                    continue 2;
                }
            }
            fwrite($handle, $this->nlt(6) . 'echo \'<td>\';');

            $tipo = filter_input(INPUT_POST, 'tipo_' . $table . '_' . $f->Field);
            $valor = '$' . substr(strtolower($table), 0, 1) . '->' . $f->Field;
            if ($tipo == 'now' || $tipo == 'datetime') {
                fwrite($handle, $this->nlt(6) . '$horario = new DateTime($' . substr(strtolower($table), 0, 1) . '->' . ($f->Field) . ');');
                $valor = 'date_format($horario, \'H:i d/m/Y\')';
            } elseif ($tipo == 'date') {
                fwrite($handle, $this->nlt(6) . '$horario = new DateTime($' . substr(strtolower($table), 0, 1) . '->' . ($f->Field) . ');');
                $valor = 'date_format($horario, \'d/m/Y\')';
            } elseif ($tipo == 'time') {
                fwrite($handle, $this->nlt(6) . '$horario = new DateTime($' . substr(strtolower($table), 0, 1) . '->' . ($f->Field) . ');');
                $valor = 'date_format($horario, \'H:i:s\')';
            } elseif ($tipo == 'password') {
                $valor = '\'**********\'';
            } elseif ($tipo == 'checkbox') {
                fwrite($handle, $this->nlt(6) . '$check = \'<i class="fa fa-times"></i>\';');
                fwrite($handle, $this->nlt(6) . 'if($' . substr(strtolower($table), 0, 1) . '->' . ($f->Field) . '){');
                fwrite($handle, $this->nlt(7) . '$check = \'<i class="fa fa-check"></i>\';');
                fwrite($handle, $this->nlt(6) . '}');
                $valor = '$check';
            } elseif ($tipo == 'image') {
                $valor = '\'<img src="\'.SITE_PATH.\'/\'.$' . substr(strtolower($table), 0, 1) . '->' . ($f->Field) . '.\'" style="max-width:50px; max-height:50px" onerror="$(this).hide();">\'';
            }
            fwrite($handle, $this->nlt(6) . 'echo $this->Html->getLink(' . $valor . ', \'' . ucfirst($table) . '\', \'ver\',');
            fwrite($handle, $this->nlt(7) . 'array($' . substr(strtolower($table), 0, 1) . '->' . $priField . '), // variaveis via GET opcionais');
            fwrite($handle, $this->nlt(7) . 'array()); // atributos HTML opcionais');
            fwrite($handle, $this->nlt(6) . 'echo \'</td>\';');
        }

        fwrite($handle, $this->nlt(6) . 'echo \'</tr>\';');
        fwrite($handle, $this->nlt(5) . '}');
        fwrite($handle, $this->nlt(5) . '?>');
        fwrite($handle, $this->nlt(4) . '</table>' . "\n");

        fwrite($handle, $this->nlt(4) . '<!-- menu de paginação -->');
        fwrite($handle, $this->nlt(4) . '<div style="text-align:center"><?php echo $' . $var . '->getNav(); ?></div>');
        fwrite($handle, $this->nlt(3) . '</div> <!-- .table-reponsive -->');
        fwrite($handle, $this->nlt(2) . '</div>  <!-- .clearfix -->' . "\n");
        fwrite($handle, $this->nlt(1) . '</div> <!-- .panel-body -->');
        fwrite($handle, $this->nlt(1) . '</div> <!-- .panel .' . ucfirst($table) . ' -->');
        fwrite($handle, $this->nlt(1) . "\n");
        fwrite($handle, $this->nlt(1) . "\n");
    }

    # listar

    private function installViewAll($table, $handle) {
        $dbschema = $this->getDbSchema($table);
        $tableschema = $this->getTableSchema($table);
        $priField = $tableschema[0]->Field;
        foreach ($tableschema as $f) {
            if ($f->Key == 'PRI') {
                $priField = $f->Field;
                break;
            }
        }

        fwrite($handle, '<?php');
        fwrite($handle, $this->nlt(0) . '# Visão view/' . ucfirst($table) . '/lista.php ');
        fwrite($handle, $this->nlt(0) . '/* @var $this ' . ucfirst($table) . 'Controller */');
        fwrite($handle, $this->nlt(0) . '/* @var $' . $this->getPlural(ucfirst($table)) . ' ' . ucfirst($table) . '[] */');
        fwrite($handle, $this->nlt(0) . '?>');
        fwrite($handle, $this->nlt(0) . '<div class="' . ucfirst($table) . ' lista panel panel-default">');
        fwrite($handle, $this->nlt(1) . '<!-- titulo da pagina -->');
        fwrite($handle, $this->nlt(1) . '<div class="panel-heading">');
        fwrite($handle, $this->nlt(2) . '<h1>' . $this->getPlural(ucfirst($table)) . '</h1>');
        fwrite($handle, $this->nlt(1) . '</div>');
        fwrite($handle, $this->nlt(2) . "\n");
        fwrite($handle, $this->nlt(1) . '<div class="panel-body">');
        fwrite($handle, $this->nlt(2) . '<!-- botao de cadastro -->');
        fwrite($handle, $this->nlt(2) . '<div class="text-right pull-right">');
        fwrite($handle, $this->nlt(3) . '<p><?php echo $this->Html->getLink(\'<i class="fa fa-plus-circle"></i> Cadastrar ' . ucfirst($table) . '\', \'' . ucfirst($table) . '\', \'cadastrar\', NULL, array(\'class\' => \'btn btn-primary\')); ?></p>');
        fwrite($handle, $this->nlt(2) . '</div>' . "\n"); # .pull-right

        fwrite($handle, $this->nlt(2) . '<!-- formulario de pesquisa -->');
        fwrite($handle, $this->nlt(2) . '<div class="pull-left">');
        fwrite($handle, $this->nlt(3) . '<form class="form-inline" role="form" method="get" action="<?php echo $this->Html->getUrl(CONTROLLER,ACTION,array(\'ordenaPor\'=>$this->getParam(\'ordenaPor\')))?>">');
        /* fwrite($handle, $this->nlt(3) . '<input type="hidden" name="m" value="<?php echo CONTROLLER; ?>">');
          fwrite($handle, $this->nlt(3) . '<input type="hidden" name="p" value="<?php echo ACTION; ?>">'); */
        fwrite($handle, $this->nlt(4) . '<div class="form-group">');
        fwrite($handle, $this->nlt(5) . '<label class="sr-only" for="pesquisa">Pesquisar</label>');
        $searchField = filter_input(INPUT_POST, 'pesquisa_' . $table);
        fwrite($handle, $this->nlt(5) . '<input value="<?php echo $this->getParam(\'pesquisa\') ?>" type="search" class="form-control" name="pesquisa" id="pesquisa" placeholder="' . filter_input(INPUT_POST, 'form_' . $table . '_' . $searchField) . '">');
        fwrite($handle, $this->nlt(4) . '</div>'); # .form-group
        fwrite($handle, $this->nlt(4) . '<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>');
        fwrite($handle, $this->nlt(3) . '</form>');
        fwrite($handle, $this->nlt(2) . '</div>' . "\n"); # .pull-left
        fwrite($handle, $this->nlt(2) . '<div class="clearfix"></div>');
        fwrite($handle, $this->nlt(2) . '<br>');

        fwrite($handle, $this->nlt(2) . '<!-- tabela de resultados -->');
        fwrite($handle, $this->nlt(2) . '<div class="table-responsive">');
        fwrite($handle, $this->nlt(3) . '<table class="table table-hover">');
        fwrite($handle, $this->nlt(4) . '<thead>');
        fwrite($handle, $this->nlt(5) . '<tr>');
        $atributos = (array) filter_input(INPUT_POST, 'colunasAll_' . $table, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach ($tableschema as $f) {
            $usar = false;
            foreach ($atributos as $atributo) {
                if ($atributo == $f->Field) {
                    $usar = true;
                    break;
                }
            }
            if (!$usar) {
                continue;
            }
            fwrite($handle, $this->nlt(6) . '<th>');
            fwrite($handle, $this->nlt(7) . '<a href=\'<?php echo $this->Html->getUrl(\'' . ucfirst($table) . '\', \'lista\', array(\'ordenaPor\' => \'' . $f->Field . '\', \'pesquisa\' => $this->getParam(\'pesquisa\') )); ?>\'>');
            fwrite($handle, $this->nlt(8) . filter_input(INPUT_POST, 'form_' . $table . '_' . $f->Field));
            fwrite($handle, $this->nlt(7) . '</a>');
            fwrite($handle, $this->nlt(6) . '</th>');
        }
        fwrite($handle, $this->nlt(6) . '<th>&nbsp;</th>');
        fwrite($handle, $this->nlt(6) . '<th>&nbsp;</th>');
        fwrite($handle, $this->nlt(5) . '</tr>');
        fwrite($handle, $this->nlt(4) . '</thead>');

        fwrite($handle, $this->nlt(4) . '<?php');
        fwrite($handle, $this->nlt(4) . 'foreach ($' . $this->getPlural(ucfirst($table)) . ' as $' . substr(strtolower($table), 0, 1) . ') {');
        fwrite($handle, $this->nlt(5) . 'echo \'<tr>\';');
        foreach ($tableschema as $f) {
            $usar = false;
            foreach ($atributos as $atributo) {
                if ($atributo == $f->Field) {
                    $usar = true;
                    break;
                }
            }
            if (!$usar) {
                continue;
            }
            $used = array('1');
            foreach ($dbschema as $dbs) {
                if ($dbs->table == $table && $dbs->fk == $f->Field) {
                    $reftableschema = $this->getTableSchema($dbs->reftable);
                    $priRefField = $reftableschema[0]->Field;
                    foreach ($reftableschema as $fref) {
                        if ($fref->Key == 'PRI') {
                            $priRefField = $fref->Field;
                            break;
                        }
                    }
                    $mname = 'get' . ucfirst(($dbs->reftable));
                    $cused = 2;
                    while (array_search($mname, $used)) {
                        $mname .= $cused;
                    }
                    $used[] = $mname;
                    fwrite($handle, $this->nlt(6) . 'echo \'<td>\';');
                    fwrite($handle, $this->nlt(6) . 'echo $this->Html->getLink($' . substr(strtolower($table), 0, 1) . '->' . $mname . '()->' . filter_input(INPUT_POST, 'colunasTitleRef_' . $table . '_' . $f->Field) . ', \'' . ucfirst(($dbs->reftable)) . '\', \'ver\',');
                    fwrite($handle, $this->nlt(7) . 'array($' . substr(strtolower($table), 0, 1) . '->' . $mname . '()->' . $priRefField . '), // variaveis via GET opcionais');
                    fwrite($handle, $this->nlt(7) . 'array()); // atributos HTML opcionais');
                    fwrite($handle, $this->nlt(6) . 'echo \'</td>\';');
                    continue 2;
                }
            }
            fwrite($handle, $this->nlt(6) . 'echo \'<td>\';');
            $tipo = filter_input(INPUT_POST, 'tipo_' . $table . '_' . $f->Field);
            $valor = '$' . substr(strtolower($table), 0, 1) . '->' . $f->Field;
            if ($tipo == 'now' || $tipo == 'datetime') {
                fwrite($handle, $this->nlt(6) . '$horario = new DateTime($' . substr(strtolower($table), 0, 1) . '->' . ($f->Field) . ');');
                $valor = 'date_format($horario, \'H:i d/m/Y\')';
            } elseif ($tipo == 'date') {
                fwrite($handle, $this->nlt(6) . '$horario = new DateTime($' . substr(strtolower($table), 0, 1) . '->' . ($f->Field) . ');');
                $valor = 'date_format($horario, \'d/m/Y\')';
            } elseif ($tipo == 'time') {
                fwrite($handle, $this->nlt(6) . '$horario = new DateTime($' . substr(strtolower($table), 0, 1) . '->' . ($f->Field) . ');');
                $valor = 'date_format($horario, \'H:i:s\')';
            } elseif ($tipo == 'password') {
                $valor = '\'**********\'';
            } elseif ($tipo == 'checkbox') {
                fwrite($handle, $this->nlt(6) . '$check = \'<i class="fa fa-times"></i>\';');
                fwrite($handle, $this->nlt(6) . 'if($' . substr(strtolower($table), 0, 1) . '->' . ($f->Field) . '){');
                fwrite($handle, $this->nlt(7) . '$check = \'<i class="fa fa-check"></i>\';');
                fwrite($handle, $this->nlt(6) . '}');
                $valor = '$check';
            } elseif ($tipo == 'image') {
                $valor = '\'<img src="\'.SITE_PATH.\'/\'.$' . substr(strtolower($table), 0, 1) . '->' . ($f->Field) . '.\'" style="max-width:50px; max-height:50px" onerror="$(this).hide();">\'';
            }
            fwrite($handle, $this->nlt(6) . 'echo $this->Html->getLink(' . $valor . ', \'' . ucfirst($table) . '\', \'ver\',');
            fwrite($handle, $this->nlt(7) . 'array($' . substr(strtolower($table), 0, 1) . '->' . $priField . '), // variaveis via GET opcionais');
            fwrite($handle, $this->nlt(7) . 'array()); // atributos HTML opcionais');
            fwrite($handle, $this->nlt(6) . 'echo \'</td>\';');
        }

        fwrite($handle, $this->nlt(6) . 'echo \'<td width="50">\';');
        fwrite($handle, $this->nlt(6) . 'echo $this->Html->getLink(\'<i class="fa fa-pencil-square-o"></i>\', \'' . ucfirst($table) . '\', \'editar\', ');
        fwrite($handle, $this->nlt(7) . 'array($' . substr(strtolower($table), 0, 1) . '->' . $priField . '), ');
        fwrite($handle, $this->nlt(7) . 'array(\'class\' => \'text-warning\', \'title\' => \'editar\'));');
        fwrite($handle, $this->nlt(6) . 'echo \'</td>\';');
        fwrite($handle, $this->nlt(6) . 'echo \'<td width="50">\';');
        fwrite($handle, $this->nlt(6) . 'echo $this->Html->getModalLink(\'<i class="fa fa-trash-o"></i>\', \'' . ucfirst($table) . '\', \'apagar\', ');
        fwrite($handle, $this->nlt(7) . 'array($' . substr(strtolower($table), 0, 1) . '->' . $priField . '), ');
        fwrite($handle, $this->nlt(7) . 'array(\'class\' => \'text-danger\', \'title\' => \'apagar\'));');
        fwrite($handle, $this->nlt(6) . 'echo \'</td>\';');
        fwrite($handle, $this->nlt(5) . 'echo \'</tr>\';');
        fwrite($handle, $this->nlt(4) . '}');
        fwrite($handle, $this->nlt(4) . '?>');
        fwrite($handle, $this->nlt(3) . '</table>' . "\n");

        fwrite($handle, $this->nlt(3) . '<!-- menu de paginação -->');
        fwrite($handle, $this->nlt(3) . '<div style="text-align:center"><?php echo $' . $this->getPlural(ucfirst($table)) . '->getNav(); ?></div>');
        fwrite($handle, $this->nlt(2) . '</div> <!-- .table-responsive -->');
        fwrite($handle, $this->nlt(1) . '</div> <!-- .panel-body -->');
        fwrite($handle, $this->nlt(0) . '</div> <!-- .panel -->' . "\n");

        fwrite($handle, $this->nlt(0) . '<script>');
        fwrite($handle, $this->nlt(1) . '/* faz a pesquisa com ajax */');
        fwrite($handle, $this->nlt(1) . '$(document).ready(function() {');
        fwrite($handle, $this->nlt(2) . '$(\'#pesquisa\').keyup(function() {');
        fwrite($handle, $this->nlt(3) . 'var r = true;');
        fwrite($handle, $this->nlt(3) . 'if (r) {');
        fwrite($handle, $this->nlt(4) . 'r = false;');
        fwrite($handle, $this->nlt(4) . '$("div.table-responsive").load(');
        fwrite($handle, $this->nlt(4) . '<?php');
        fwrite($handle, $this->nlt(4) . 'if (isset($_GET[\'ordenaPor\']))');
        fwrite($handle, $this->nlt(5) . 'echo \'"\' . $this->Html->getUrl(\'' . ucfirst($table) . '\', \'lista\', array(\'ordenaPor\' => $_GET[\'ordenaPor\'])) . \'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"\';');
        fwrite($handle, $this->nlt(4) . 'else');
        fwrite($handle, $this->nlt(5) . 'echo \'"\' . $this->Html->getUrl(\'' . ucfirst($table) . '\', \'lista\') . \'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"\';');
        fwrite($handle, $this->nlt(4) . '?>');
        fwrite($handle, $this->nlt(4) . ' , function() {');
        fwrite($handle, $this->nlt(5) . 'r = true;');
        fwrite($handle, $this->nlt(4) . '});');
        fwrite($handle, $this->nlt(3) . '}');
        fwrite($handle, $this->nlt(2) . '});');
        fwrite($handle, $this->nlt(1) . '});');
        fwrite($handle, $this->nlt(0) . '</script>');
        fwrite($handle, $this->nlt(0) . '<!-- LazyPHP.com.br -->');
    }

    private function installViewAdd($table, $handle) {
        $dbschema = $this->getDbSchema($table);
        $tableschema = $this->getTableSchema($table);

        fwrite($handle, '<?php');
        fwrite($handle, $this->nlt(0) . '# Visão view/' . ucfirst($table) . '/cadastrar.php');
        fwrite($handle, $this->nlt(0) . '/* @var $this ' . ucfirst($table) . 'Controller */');
        fwrite($handle, $this->nlt(0) . '/* @var $' . (ucfirst($table)) . ' ' . ucfirst($table) . ' */');
        fwrite($handle, $this->nlt(0) . '?>');

        fwrite($handle, $this->nlt(0) . '<div class="' . ucfirst($table) . ' cadastrar panel panel-default">');
        fwrite($handle, $this->nlt(1) . '<div class="panel-heading">');
        fwrite($handle, $this->nlt(2) . '<h1>Cadastrar ' . ucfirst($table) . '</h1>');
        fwrite($handle, $this->nlt(1) . '</div>');
        fwrite($handle, $this->nlt(1) . '<div class="panel-body">');
        fwrite($handle, $this->nlt(1) . '<form class="form-horizontal" method="post" role="form" action="<?php echo $this->Html->getUrl(\'' . ucfirst($table) . '\', \'cadastrar\') ?>"  enctype="multipart/form-data">');
        fwrite($handle, $this->nlt(1) . '<div class="text-info">Os campos marcados com <i class="fa fa-asterisk"></i> são de preenchimento obrigatório.</div>');
        fwrite($handle, $this->nlt(1) . '<br>');
        fwrite($handle, $this->nlt(2) . '<?php');
        foreach ($tableschema as $f) {
            $label = filter_input(INPUT_POST, 'form_' . $table . '_' . $f->Field);
            $tipo = filter_input(INPUT_POST, 'tipo_' . $table . '_' . $f->Field);
            
            foreach ($dbschema as $dbs) {
                // fk
                if ($dbs->table == $table && $dbs->fk == $f->Field) {
                    $reftableschema = $this->getTableSchema($dbs->reftable);
                    $strreftable = $reftableschema[0]->Field;
                    foreach ($reftableschema as $fref) {
                        if (strstr($fref->Type, 'char')) {
                            $strreftable = $fref->Field;
                            break;
                        }
                    }           
                    if ($tipo == 'radioFK') {                        
                        fwrite($handle, $this->nlt(2) . '# '.$f->Field);  
                        fwrite($handle, $this->nlt(2) . 'if ($this->getParam(\''.$dbs->fk.'\')) {');
                        fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormHidden(\'' . $dbs->fk . '\', $this->getParam(\'' . $dbs->fk . '\'));');
                        fwrite($handle, $this->nlt(2) . '} else {');
                        fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormInputRadio(\''.$label.'\', \'' . $dbs->fk . '\', array_columns((array) $' . $this->getPlural(ucfirst(($dbs->reftable))) . ',\''.$strreftable.'\', \'' . $dbs->refpk . '\'));');
                        fwrite($handle, $this->nlt(2) . '}');
                    } else {                        
                        fwrite($handle, $this->nlt(2) . '# '.$f->Field);  
                        fwrite($handle, $this->nlt(2) . 'if ($this->getParam(\''.$dbs->fk.'\')) {');
                        fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormHidden(\'' . $dbs->fk . '\', $this->getParam(\'' . $dbs->fk . '\'));');
                        fwrite($handle, $this->nlt(2) . '} else {');
                        fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormSelect(\''.$label.'\', \'' . $dbs->fk . '\', array_columns((array) $' . $this->getPlural(ucfirst(($dbs->reftable))) . ',\''.$strreftable.'\', \'' . $dbs->refpk . '\'));');
                        fwrite($handle, $this->nlt(2) . '}');                        
                    }
                    continue 2;
                }
            }                      
            if ($f->Key == 'PRI') {
                continue;
            }
            if ($tipo == 'now') {
                continue;
            }            
            fwrite($handle, $this->nlt(2) . '# '.$f->Field);  
            $req = 'false';
            $reqLabel = '';
            if ($f->Null == 'NO') {
                $req = 'true';
                $reqLabel = ' <small><i class="fa fa-asterisk"></i></small>';
            }
            #fwrite($handle, $this->nlt(2) . 'if($this->getParam(\'' . $f->Field . '\')){');
            #fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormHidden(\'' . $f->Field . '\', $this->getParam(\'' . $f->Field . '\'));');
            #fwrite($handle, $this->nlt(2) . '} else {');            
            if ($tipo == 'textarea_html') {
                fwrite($handle, $this->nlt(2) . 'echo $this->Html->getFormTextareaHtml(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'\', '.$req.');');         
            } elseif ($tipo == 'textarea') {
                fwrite($handle, $this->nlt(2) . 'echo $this->Html->getFormTextarea(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'\', '.$req.');');
            } elseif ($tipo == 'number') {
                fwrite($handle, $this->nlt(2) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'number\', \'\', '.$req.');');
            } elseif ($tipo == 'date') {
                fwrite($handle, $this->nlt(2) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'date\', \'\', '.$req.');');
            } elseif ($tipo == 'datetime') {
                fwrite($handle, $this->nlt(2) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'datetime-local\', \'\', '.$req.');');
            } elseif ($tipo == 'time') {
                fwrite($handle, $this->nlt(2) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'time\', \'\', '.$req.');');
            } elseif ($tipo == 'password') {
                fwrite($handle, $this->nlt(2) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'password\', \'\', '.$req.');');
            } elseif ($tipo == 'checkbox') {
                fwrite($handle, $this->nlt(2) . 'echo $this->Html->getFormInputCheckbox(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ');');
            } elseif ($tipo == 'decimal') {
                fwrite($handle, $this->nlt(2) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'decimal\', \'\', '.$req.');');
            } elseif ($tipo == 'email') {
                fwrite($handle, $this->nlt(2) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'email\', \'\', '.$req.');');
            } elseif ($tipo == 'image' || $tipo == 'file') {
                fwrite($handle, $this->nlt(2) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'file\', \'\', '.$req.');');
            } else {
                fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'text\', \'\', '.$req.');');
            }   
            #fwrite($handle, $this->nlt(2) . '}');
            
        }        
        fwrite($handle, $this->nlt(2) . 'if($this->getParam(\'url_origem\')){');
        fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormHidden(\'url_origem\', $this->getParam(\'url_origem\'));');
        fwrite($handle, $this->nlt(2) . '}');
        fwrite($handle, $this->nlt(2) . '?>');
        fwrite($handle, $this->nlt(2) . '<div class="clearfix"></div>');
        fwrite($handle, $this->nlt(2) . '<div class="text-right">');        
        fwrite($handle, $this->nlt(2) . '<?php');
        fwrite($handle, $this->nlt(2) . 'if($this->getParam(\'url_origem\')){');
        fwrite($handle, $this->nlt(3) . '$url_destino = Cript::decript($this->getParam(\'url_origem\'));');
        fwrite($handle, $this->nlt(2) . '} else {');     
        fwrite($handle, $this->nlt(3) . '$url_destino = $this->Html->getUrl(\'' . ucfirst($table) . '\', \'lista\');');
        fwrite($handle, $this->nlt(2) . '} ?>');  
        fwrite($handle, $this->nlt(3) . '<a href="<?php echo $url_destino ?>" class="btn btn-default" data-dismiss="modal">Cancelar</a>');
        fwrite($handle, $this->nlt(3) . '<input type="submit" class="btn btn-primary" value="salvar">');
        fwrite($handle, $this->nlt(2) . '</div>');
        fwrite($handle, $this->nlt(1) . '</form>');
        fwrite($handle, $this->nlt(1) . '</div> <!-- .panel-body -->');
        fwrite($handle, $this->nlt(0) . '</div> <!-- .panel -->');
        fwrite($handle, $this->nlt(0) . '<!-- LazyPHP.com.br -->');
    }

    private function installViewEdit($table, $handle) {
        $dbschema = $this->getDbSchema($table);
        $tableschema = $this->getTableSchema($table);
        $priField = $tableschema[0]->Field;
        foreach ($tableschema as $f) {
            if ($f->Type == 'PRI') {
                $priField = $f->Field;
                break;
            }
        }
        fwrite($handle, '<?php');
        fwrite($handle, $this->nlt(0) . '# Visão view/' . ucfirst($table) . '/editar.php');
        fwrite($handle, $this->nlt(0) . '/* @var $this ' . ucfirst($table) . 'Controller */');
        fwrite($handle, $this->nlt(0) . '/* @var $' . (ucfirst($table)) . ' ' . ucfirst($table) . ' */');
        fwrite($handle, $this->nlt(0) . '?>');

        fwrite($handle, $this->nlt(0) . '<div class="' . ucfirst($table) . ' editar panel panel-default">');
        fwrite($handle, $this->nlt(1) . '<div class="panel-heading">');
        fwrite($handle, $this->nlt(2) . '<h1>Editar ' . ucfirst($table) . '</h1>');
        fwrite($handle, $this->nlt(1) . '</div>');
        fwrite($handle, $this->nlt(1) . '<div class="panel-body">');
        fwrite($handle, $this->nlt(1) . '<form class="form-horizontal" method="post" role="form" action="<?php echo $this->Html->getUrl(\'' . ucfirst($table) . '\', \'editar\', array($'.ucfirst($table).'->'.$priField.')) ?>"  enctype="multipart/form-data">');
        fwrite($handle, $this->nlt(1) . '<div class="text-info">Os campos marcados com <i class="fa fa-asterisk"></i> são de preenchimento obrigatório.</div>');
        fwrite($handle, $this->nlt(1) . '<br>');
        fwrite($handle, $this->nlt(2) . '<?php');
        foreach ($tableschema as $f) {
            $label = filter_input(INPUT_POST, 'form_' . $table . '_' . $f->Field);
            $tipo = filter_input(INPUT_POST, 'tipo_' . $table . '_' . $f->Field);
            
            foreach ($dbschema as $dbs) {
                // fk
                if ($dbs->table == $table && $dbs->fk == $f->Field) {
                    $reftableschema = $this->getTableSchema($dbs->reftable);
                    $strreftable = $reftableschema[0]->Field;
                    foreach ($reftableschema as $fref) {
                        if (strstr($fref->Type, 'char')) {
                            $strreftable = $fref->Field;
                            break;
                        }
                    }           
                    if ($tipo == 'radioFK') {                        
                        fwrite($handle, $this->nlt(2) . '# '.$f->Field);  
                        fwrite($handle, $this->nlt(2) . 'if ($this->getParam(\''.$dbs->fk.'\')) {');
                        fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormHidden(\'' . $dbs->fk . '\', $this->getParam(\'' . $dbs->fk . '\'));');
                        fwrite($handle, $this->nlt(2) . '} else {');
                        fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormInputRadio(\''.$label.'\', \'' . $dbs->fk . '\', array_columns((array) $' . $this->getPlural(ucfirst(($dbs->reftable))) . ',\''.$strreftable.'\', \'' . $dbs->refpk . '\'),$' . ucfirst($table) . '->' . $f->Field . ');');
                        fwrite($handle, $this->nlt(2) . '}');
                    } else {                        
                        fwrite($handle, $this->nlt(2) . '# '.$f->Field);  
                        fwrite($handle, $this->nlt(2) . 'if ($this->getParam(\''.$dbs->fk.'\')) {');
                        fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormHidden(\'' . $dbs->fk . '\', $this->getParam(\'' . $dbs->fk . '\'));');
                        fwrite($handle, $this->nlt(2) . '} else {');
                        fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormSelect(\''.$label.'\', \'' . $dbs->fk . '\', array_columns((array) $' . $this->getPlural(ucfirst(($dbs->reftable))) . ',\''.$strreftable.'\', \'' . $dbs->refpk . '\'));');
                        fwrite($handle, $this->nlt(2) . '}');                        
                    }
                    continue 2;
                }
            }                      
            if ($f->Key == 'PRI') {
                fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormHidden(\'' . $f->Field . '\', $' . ucfirst($table) . '->' . $f->Field . ');');
                continue;
            }
            if ($tipo == 'now') {
                continue;
            }            
            fwrite($handle, $this->nlt(2) . '# '.$f->Field);  
            $req = 'false';
            $reqLabel = '';
            if ($f->Null == 'NO') {
                $req = 'true';
                $reqLabel = ' <small><i class="fa fa-asterisk"></i></small>';
            }
            fwrite($handle, $this->nlt(2) . 'if($this->getParam(\'' . $f->Field . '\')){');
            fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormHidden(\'' . $f->Field . '\', $this->getParam(\'' . $f->Field . '\'));');
            fwrite($handle, $this->nlt(2) . '} else {');            
            if ($tipo == 'textarea_html') {
                fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormTextareaHtml(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'\', '.$req.');');         
            } elseif ($tipo == 'textarea') {
                fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormTextarea(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'\', '.$req.');');
            } elseif ($tipo == 'number') {
                fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'number\', \'\', '.$req.');');
            } elseif ($tipo == 'date') {
                fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'date\', \'\', '.$req.');');
            } elseif ($tipo == 'datetime') {
                fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'datetime-local\', \'\', '.$req.');');
            } elseif ($tipo == 'time') {
                fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'time\', \'\', '.$req.');');
            } elseif ($tipo == 'password') {
                fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'password\', \'\', '.$req.');');
            } elseif ($tipo == 'checkbox') {
                fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormInputCheckbox(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ');');
            } elseif ($tipo == 'decimal') {
                fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'decimal\', \'\', '.$req.');');
            } elseif ($tipo == 'email') {
                fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'email\', \'\', '.$req.');');
            } elseif ($tipo == 'image' || $tipo == 'file') {
                fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'file\', \'\', '.$req.');');
            } else {
                fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormInput(\''.  $label.'\', \''.$f->Field.'\', $' . ucfirst($table) . '->' . $f->Field . ', \'text\', \'\', '.$req.');');
            }   
            fwrite($handle, $this->nlt(2) . '}');
            
        }        
        fwrite($handle, $this->nlt(2) . 'if($this->getParam(\'url_origem\')){');
        fwrite($handle, $this->nlt(3) . 'echo $this->Html->getFormHidden(\'url_origem\', $this->getParam(\'url_origem\'));');
        fwrite($handle, $this->nlt(2) . '}');
        fwrite($handle, $this->nlt(2) . '?>');
        fwrite($handle, $this->nlt(2) . '<div class="clearfix"></div>');
        fwrite($handle, $this->nlt(2) . '<div class="text-right">');
        
        fwrite($handle, $this->nlt(2) . '<?php');
        fwrite($handle, $this->nlt(2) . 'if($this->getParam(\'url_origem\')){');
        fwrite($handle, $this->nlt(3) . '$url_destino = Cript::decript($this->getParam(\'url_origem\'));');
        fwrite($handle, $this->nlt(2) . '} else {');     
        fwrite($handle, $this->nlt(3) . '$url_destino = $this->Html->getUrl(\'' . ucfirst($table) . '\', \'lista\');');
        fwrite($handle, $this->nlt(2) . '} ?>');  
        fwrite($handle, $this->nlt(3) . '<a href="<?php echo $url_destino ?>" class="btn btn-default" data-dismiss="modal">Cancelar</a>');
        fwrite($handle, $this->nlt(3) . '<input type="submit" class="btn btn-primary" value="salvar">');
        fwrite($handle, $this->nlt(2) . '</div>');
        fwrite($handle, $this->nlt(1) . '</form>');
        fwrite($handle, $this->nlt(1) . '</div> <!-- .panel-body -->');
        fwrite($handle, $this->nlt(0) . '</div> <!-- .panel -->');
        fwrite($handle, $this->nlt(0) . '<!-- LazyPHP.com.br -->');
    }

    private function installViewDelete($table, $handle) {
        $tableschema = $this->getTableSchema($table);

        $stringField = $tableschema[0]->Field;
        $priField = $tableschema[0]->Field;
        foreach ($tableschema as $f) {
            if (strstr($f->Type, 'char')) {
                $stringField = $f->Field;
                break;
            }
        }
        foreach ($tableschema as $f) {
            if ($f->Type == 'PRI') {
                $priField = $f->Field;
                break;
            }
        }

        fwrite($handle, '<?php');
        fwrite($handle, $this->nlt(0) . '# Visão view/' . ucfirst($table) . '/apagar.php ');
        fwrite($handle, $this->nlt(0) . '/* @var $this ' . ucfirst($table) . 'Controller */');
        fwrite($handle, $this->nlt(0) . '/* @var $' . (ucfirst($table)) . ' ' . ucfirst($table) . ' */');
        fwrite($handle, $this->nlt(0) . '?>');
        fwrite($handle, $this->nlt(0) . '<form class="form" method="post" action="<?php echo $this->Html->getUrl(\'' . ucfirst($table) . '\', \'apagar\') ?>">');
        fwrite($handle, $this->nlt(1) . '<h1>Confirmação</h1>');
        fwrite($handle, $this->nlt(1) . '<div class="well well-lg">');
        fwrite($handle, $this->nlt(2) . '<p>Voce tem certeza que deseja excluir o ' . ucfirst($table) . ' <strong><?php echo $' . ucfirst($table) . '->' . $stringField . '; ?></strong>?</p>');
        fwrite($handle, $this->nlt(1) . '</div>');
        fwrite($handle, $this->nlt(1) . '<div class="text-right">');
        fwrite($handle, $this->nlt(2) . '<input type="hidden" name="id" value="<?php echo $' . ucfirst($table) . '->' . $priField . '; ?>">');
        fwrite($handle, $this->nlt(2) . '<a href="<?php echo $this->Html->getUrl(\'' . ucfirst($table) . '\', \'lista\') ?>" class="btn btn-default" data-dismiss="modal">Cancelar</a>');
        fwrite($handle, $this->nlt(2) . '<input type="submit" class="btn btn-danger" value="Excluir">');
        fwrite($handle, $this->nlt(1) . '</div>');
        fwrite($handle, $this->nlt(1) . '<?php');
        fwrite($handle, $this->nlt(2) . 'echo $this->Html->getFormHidden(\'url_origem\', $this->getParam(\'url_origem\'));');
        fwrite($handle, $this->nlt(1) . '?>');
        fwrite($handle, $this->nlt(0) . '</form>');
        fwrite($handle, $this->nlt(0) . '<!-- LazyPHP.com.br -->');
    }

    private function installMenu() {
        $handle = fopen("template/menu.php", 'w');
        $menus = (array) filter_input(INPUT_POST, 'menus', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach ($menus as $menu => $valor) {
            fwrite($handle, $this->nlt(1) . '<li>');
            fwrite($handle, $this->nlt(1) . '<?php echo $this->Html->getLink(\'' . $menu . '\', \'' . ucfirst(($menu)) . '\', \'lista\'); ?>');
            fwrite($handle, $this->nlt(1) . '</li>');
        }
        fclose($handle);
    }

    private function checkDB() {
        $tables = $this->getTables();
        $ok = true;
        foreach ($tables as $table) {
            $schema = $this->getTableSchema($table->name);
            $pris = 0;
            foreach ($schema as $t) {
                if ($t->Key == 'PRI') {
                    if ($t->Extra != 'auto_increment') {
                        new Msg('A chave primária da tabela <strong>' . $table->name . '</strong> deve ser AUTO-INCREMENT', 3);
                        $ok = false;
                    }
                    $pris++;
                    if ($pris != 1) {
                        new Msg('Cada tabela do seu banco deve ter uma chave primária auto-increment única.<br>Verifique a tabela <strong>' . $table->name . '</strong>', 3);
                        $ok = false;
                    }
                }
            }
            if ($pris == 0) {
                new Msg('A tabela <strong>' . $table->name . '</strong> não possui uma chave primária auto-increment.', 3);
                $ok = false;
            }
        }
        return $ok;
    }

    public function getTables() {
        return $this->query('SELECT table_name AS name FROM information_schema.tables WHERE table_schema = DATABASE()');
    }

    public function getPlural($nome) {
        if (substr($nome, -1) == "s") {
            return $nome;
        }
        if (substr($nome, -1) == "r") {
            return $nome . "es";
        }
        if (substr($nome, -1) == "m") {
            return substr($nome, 0, -1) . "ns";
        }
        if (substr($nome, -1) == "il") {
            return substr($nome, 0, -2) . "is";
        }
        if (substr($nome, -1) == "l") {
            return substr($nome, 0, -1) . "is";
        }
        return $nome . "s";
    }

    public function getDbSchema() {
        if (is_null($this->dbschema)) {
            $data = $this->query('select database() as db');
            $db = $data[0]->db;
            $data = $this->query("SELECT table_name AS 'table',  column_name AS  'fk', 
            referenced_table_name AS 'reftable', referenced_column_name  AS 'refpk' 
            FROM information_schema.key_column_usage
            WHERE referenced_table_name IS NOT NULL 
            AND TABLE_SCHEMA='" . $db . "' ");
            $this->dbschema = $data;
        }
        return $this->dbschema;
    }

    public function getTableSchema($table) {
        if (!isset($this->tableSchema[$table])) {
            return $this->tableSchema[$table] = $this->query('describe ' . $table);
        }
        return $this->tableSchema[$table];
    }

    private function nlt($n) {
        $r = "\n";
        for ($i = 0; $i < $n; $i++) {
            $r = $r . "    ";
        }
        return $r;
    }

    private function t($n) {
        for ($i = 0; $i < $n; $i++) {
            $r = $r . "    ";
        }
        return $r;
    }

}
