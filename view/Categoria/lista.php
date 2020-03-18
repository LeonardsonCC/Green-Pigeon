<?php
# Visão view/Categoria/lista.php 
/* @var $this CategoriaController */
/* @var $Categorias Categoria[] */
?>
<div class="Categoria lista panel panel-default">
    <!-- titulo da pagina -->
    <div class="panel-heading">
        <h1>Categorias</h1>
    </div>
        

    <div class="panel-body">
        <!-- botao de cadastro -->
        <div class="text-right pull-right">
            <p><?php echo $this->Html->getLink('<i class="fa fa-plus-circle"></i> Cadastrar Categoria', 'Categoria', 'cadastrar', NULL, array('class' => 'btn btn-primary')); ?></p>
        </div>

        <!-- formulario de pesquisa -->
        <div class="pull-left">
            <form class="form-inline" role="form" method="get" action="<?php echo $this->Html->getUrl(CONTROLLER,ACTION,array('ordenaPor'=>$this->getParam('ordenaPor')))?>">
                <div class="form-group">
                    <label class="sr-only" for="pesquisa">Pesquisar</label>
                    <input value="<?php echo $this->getParam('pesquisa') ?>" type="search" class="form-control" name="pesquisa" id="pesquisa" placeholder="Nome">
                </div>
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
            </form>
        </div>

        <div class="clearfix"></div>
        <br>
        <!-- tabela de resultados -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>
                            <a href='<?php echo $this->Html->getUrl('Categoria', 'lista', array('ordenaPor' => 'nome', 'pesquisa' => $this->getParam('pesquisa') )); ?>'>
                                Nome
                            </a>
                        </th>
                        <th>
                            <a href='<?php echo $this->Html->getUrl('Categoria', 'lista', array('ordenaPor' => 'link', 'pesquisa' => $this->getParam('pesquisa') )); ?>'>
                                Link
                            </a>
                        </th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <?php
                foreach ($Categorias as $c) {
                    echo '<tr>';
                        echo '<td>';
                        echo $this->Html->getLink($c->nome, 'Categoria', 'ver',
                            array($c->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '<td>';
                        echo $this->Html->getLink($c->link, 'Categoria', 'ver',
                            array($c->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '<td width="50">';
                        echo $this->Html->getLink('<i class="fa fa-pencil-square-o"></i>', 'Categoria', 'editar', 
                            array($c->id), 
                            array('class' => 'text-warning', 'title' => 'editar'));
                        echo '</td>';
                        echo '<td width="50">';
                        echo $this->Html->getModalLink('<i class="fa fa-trash-o"></i>', 'Categoria', 'apagar', 
                            array($c->id), 
                            array('class' => 'text-danger', 'title' => 'apagar'));
                        echo '</td>';
                    echo '</tr>';
                }
                ?>
            </table>

            <!-- menu de paginação -->
            <div style="text-align:center"><?php echo $Categorias->getNav(); ?></div>
        </div> <!-- .table-responsive -->
    </div> <!-- .panel-body -->
</div> <!-- .panel -->

<script>
    /* faz a pesquisa com ajax */
    $(document).ready(function() {
        $('#pesquisa').keyup(function() {
            var r = true;
            if (r) {
                r = false;
                $("div.table-responsive").load(
                <?php
                if (isset($_GET['ordenaPor']))
                    echo '"' . $this->Html->getUrl('Categoria', 'lista', array('ordenaPor' => $_GET['ordenaPor'])) . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
                else
                    echo '"' . $this->Html->getUrl('Categoria', 'lista') . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
                ?>
                 , function() {
                    r = true;
                });
            }
        });
    });
</script>
<!-- LazyPHP.com.br -->