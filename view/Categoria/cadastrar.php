<?php
# Visão view/Categoria/cadastrar.php
/* @var $this CategoriaController */
/* @var $Categoria Categoria */
?>
<div class="Categoria container" style="margin-top: 10vh">
    <div class="row">
        <div class="col s12 m6 l6 offset-m3 offset-l3 form-cadastrar">
            <h3>Cadastrar Categoria</h3>
            <div class="">
                <form method="post" action="<?php echo $this->Html->getUrl('Categoria', 'cadastrar') ?>"  enctype="multipart/form-data">
                    <?php
                    # nome
                    echo $this->Html->getFormInput('Nome', 'nome', $Categoria->nome, 'text', '', true);
                    ?>
                    <div class="form-enviar right">
                        <?php
                        if ($this->getParam('url_origem')) {
                            $url_destino = Cript::decript($this->getParam('url_origem'));
                        } else {
                            $url_destino = $this->Html->getUrl('Categoria', 'lista');
                        }
                        ?>
                        <a href="<?php echo $url_destino ?>" class="btn-flat white-text waves-effect waves-red" data-dismiss="modal">Cancelar</a>
                        <button type="submit" class="btn green" value="">Salvar</button>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</div>