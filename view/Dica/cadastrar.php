<?php
# Visão view/Dica/cadastrar.php
/* @var $this DicaController */
/* @var $Dica Dica */
?>
<div class="Dica container" style="margin-top: 10vh; margin-top: 10vh">
    <div class="row">
        <div class="col s12 m6 l6 offset-m3 offset-l3 form-cadastrar">
            <h3>Cadastrar dica</h3>
            <div class="">
                <form method="post" action="<?php echo $this->Html->getUrl('Dica', 'cadastrar') ?>"  enctype="multipart/form-data">
                    <?php
                    # titulo
                    echo $this->Html->getFormInput('Título', 'titulo', $Dica->titulo, 'text', '', true);
                    # categoria_id
                    if ($this->getParam('categoria_id')) {
                        echo $this->Html->getFormHidden('categoria_id', $this->getParam('categoria_id'));
                    } else {
                        echo $this->Html->getFormSelect('Categoria', 'categoria_id', array_columns((array) $Categorias, 'nome', 'id'));
                    }
                    # texto
                    echo $this->Html->getFormTextareaHtml('Texto', 'texto', $Dica->texto, 'Escreva o texto da dica', true);
                    echo '<br>';
                    # criador_id
                    $criador = Session::get('user');
                    echo $this->Html->getFormHidden('criador_id', $criador->id);
                    ?>
                    <div class = "form-enviar right">
                        <a href="<?php echo $url_destino ?>" class="btn-flat white-text waves-effect waves-red" data-dismiss="modal">Cancelar</a>
                        <button type="submit" class="btn green" value="">Salvar</button>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</div>
