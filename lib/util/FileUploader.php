<?php

class FileUploader {

    public $name;
    public $path;
    private $file = NULL;
    public $erro = NULL;
    private $ext = '';

    /**
     * 
     * @param array $file $_FILE do arquivo
     * @param type $maxsize tamanho máximo em bytes
     * @param array $extensions extesões permitidas
     * @throws Exception
     */
    public function __construct($file, $maxsize = NULL, Array $extensions = NULL) {
        if (is_uploaded_file($file['tmp_name'])) {
            if (!is_null($maxsize) && $file["size"] > $maxsize) {
                throw new Exception(__('O arquivo é muito grande.'));
                return;
            }

            $this->ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (!is_null($extensions)) {
                $valid = false;
                foreach ($extensions as $mime) {
                    if ($this->ext == $mime) {
                        $valid = TRUE;
                        break;
                    }
                }
                if (!$valid) {
                    throw new Exception(__('Tipo de arquivo não permitido.'));
                    return;
                }
            }
            $this->file = $file;
        }
    }

    /**
     * Salva o arquivo.
     * 
     * @param String $filename novo nome
     * @param String $path nome do diretório onde irá salvar (pasta Upload)
     * @return boolean
     * @throws Exception
     */
    function save($filename, $path) {
        $filename = filter_var($filename, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (is_null($this->file)) {
            throw new Exception(__('Nenhum arquivo enviado.'));
        }
        $filename = str_replace('.', '', $filename);
        $filename = str_replace(' ', '_', $filename);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (empty($ext) || $ext == $filename) {
            $filename .= '.' . $this->ext;
        }

        $this->path = 'uploads/' . $path;
        $this->name = $filename;
        if (!is_dir($this->path) && !file_exists($this->path)){
            mkdir($this->path, 755, true);
        }

        if (!move_uploaded_file($this->file['tmp_name'], $this->path . '/' . $filename)) {
            throw new Exception(__('Arquivo não enviado. Erro de permissão.'));
        }
        return ($this->path . '/' . $filename);
    }

}

?>
