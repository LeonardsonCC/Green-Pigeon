<?php

class ImageUploader {

    private $x = 0;
    private $y = 0;
    public $nome;
    public $path;
    private $image;
    public $erro = null;

    /**
     * Prepara uma imagem para upload. Aceita apenas JPG, PNG ou GIF
     * 
     * @param array $imagem $_FILE  da imagem enviada
     * @param type $maxwidth largura máxima em pixels
     * @throws Exception
     */
    public function __construct($imagem, $maxwidth) {
        if (is_uploaded_file($imagem['tmp_name'])) {
            $mime = $imagem['type'];
            if (($mime == "image/jpeg") || ($mime == "image/pjpeg") || ($mime == "image/png") || ($mime == "image/gif")) {
                $this->image = $imagem;
                list($larg_orig, $alt_orig) = @getimagesize($this->image['tmp_name']);
                $this->y = $alt_orig;
                $this->x = $larg_orig;
            } else {
                throw new Exception(__('Formato de imagem não suportado'));
                return;
            }
        }
        if (empty($this->image)) {
            throw new Exception(__('Imagem não enviada'));
            return;
        }
        list($larg_orig, $alt_orig) = @getimagesize($this->image['tmp_name']);
        $razao_orig = $larg_orig / $alt_orig;
        $this->y = $maxwidth / $razao_orig;
        $this->x = $maxwidth;
        if ($this->y > 1000)
            $this->y = 1000;
    }

    /**
     * Salva a imagem enviada
     * 
     * @param String $name novo nome da imagem
     * @param String $path direitório (raiz dir uploads)
     * @return false|full_name
     * @throws Exception
     */
    public function save($name, $path) {
        $this->caminho = 'uploads/' . $path;
        $this->nome = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $this->nome = $name;
        if (!is_dir($this->caminho) && !file_exists($this->caminho))
            mkdir($this->caminho, 755, true);
        list($larg_orig, $alt_orig) = @getimagesize($this->image['tmp_name']);
        $imagem_nova = imagecreatetruecolor($this->x, $this->y);
        $mime = $this->image['type'];
        if (($mime == "image/jpeg") || ($mime == "image/pjpeg")) {
            $this->nome .= '.jpg';
            $imagem = imagecreatefromjpeg($this->image['tmp_name']);
            imagecopyresampled($imagem_nova, $imagem, 0, 0, 0, 0, $this->x, $this->y, $larg_orig, $alt_orig);
            if ($larg_orig <= $this->x) {
                move_uploaded_file($this->image['tmp_name'], $this->caminho . '/' . $this->nome );
                return str_replace('//', '/', $this->caminho . '/' . $this->nome );
            } else
            if (imagejpeg($imagem_nova, $this->caminho . '/' . $this->nome ))
                return str_replace('//', '/', $this->caminho . '/'. $this->nome );
        }
        if (($mime == "image/png")) {
            $this->nome .= '.png';
            $imagem = imagecreatefrompng($this->image['tmp_name']);
            imagealphablending($imagem_nova, false);
            imagesavealpha($imagem_nova, true);
            imagecopyresampled($imagem_nova, $imagem, 0, 0, 0, 0, $this->x, $this->y, $larg_orig, $alt_orig);
            if ($larg_orig <= $this->x) {
                move_uploaded_file($this->image['tmp_name'], $this->caminho . '/' . $this->nome );
                return str_replace('//', '/', $this->caminho . '/' . $this->nome );
            } else
            if (imagepng($imagem_nova, $this->caminho . '/' . $this->nome ))
                return str_replace('//', '/', $this->caminho . '/' . $this->nome );
        }
        if (($mime == "image/gif")) {
            $this->nome .= '.gif';
            $imagem = imagecreatefromgif($this->image['tmp_name']);
            imagecopyresampled($imagem_nova, $imagem, 0, 0, 0, 0, $this->x, $this->y, $larg_orig, $alt_orig);
            if ($larg_orig <= $this->x) {
                move_uploaded_file($this->image['tmp_name'], $this->caminho . '/' . $this->nome );
                return str_replace('//', '/', $this->caminho . '/' . $this->nome );
            } else
            if (imagegif($imagem_nova, $this->caminho . '/' . $this->nome ))
                return str_replace('//', '/', $this->caminho . '/' . $this->nome );
        }
        throw new Exception(__('Não foi possível salvar a imagem'));
    }

}
