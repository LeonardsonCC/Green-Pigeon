<?php
# Visão view/Feedback/lista.php 
/* @var $this FeedbackController */
/* @var $Feedbacks Feedback[] */
?>
<div class="Feedback lista panel panel-default">
    <!-- titulo da pagina -->
    <div class="panel-heading">
        <h1>Feedbacks</h1>
    </div>
        

    <div class="panel-body">
        <!-- botao de cadastro -->
        <div class="text-right pull-right">
            <p><?php echo $this->Html->getLink('<i class="fa fa-plus-circle"></i> Cadastrar Feedback', 'Feedback', 'cadastrar', NULL, array('class' => 'btn btn-primary')); ?></p>
        </div>

        <!-- formulario de pesquisa -->
        <div class="pull-left">
            <form class="form-inline" role="form" method="get" action="<?php echo $this->Html->getUrl(CONTROLLER,ACTION,array('ordenaPor'=>$this->getParam('ordenaPor')))?>">
                <div class="form-group">
                    <label class="sr-only" for="pesquisa">Pesquisar</label>
                    <input value="<?php echo $this->getParam('pesquisa') ?>" type="search" class="form-control" name="pesquisa" id="pesquisa" placeholder="Texto">
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
                            <a href='<?php echo $this->Html->getUrl('Feedback', 'lista', array('ordenaPor' => 'texto', 'pesquisa' => $this->getParam('pesquisa') )); ?>'>
                                Texto
                            </a>
                        </th>
                        <th>
                            <a href='<?php echo $this->Html->getUrl('Feedback', 'lista', array('ordenaPor' => 'email', 'pesquisa' => $this->getParam('pesquisa') )); ?>'>
                                Email
                            </a>
                        </th>
                        <th>
                            <a href='<?php echo $this->Html->getUrl('Feedback', 'lista', array('ordenaPor' => 'receber_email', 'pesquisa' => $this->getParam('pesquisa') )); ?>'>
                                Receber_email
                            </a>
                        </th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <?php
                foreach ($Feedbacks as $f) {
                    echo '<tr>';
                        echo '<td>';
                        echo $this->Html->getLink($f->texto, 'Feedback', 'ver',
                            array($f->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '<td>';
                        echo $this->Html->getLink($f->email, 'Feedback', 'ver',
                            array($f->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '<td>';
                        echo $this->Html->getLink($f->receber_email, 'Feedback', 'ver',
                            array($f->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '<td width="50">';
                        echo $this->Html->getLink('<i class="fa fa-pencil-square-o"></i>', 'Feedback', 'editar', 
                            array($f->id), 
                            array('class' => 'text-warning', 'title' => 'editar'));
                        echo '</td>';
                        echo '<td width="50">';
                        echo $this->Html->getModalLink('<i class="fa fa-trash-o"></i>', 'Feedback', 'apagar', 
                            array($f->id), 
                            array('class' => 'text-danger', 'title' => 'apagar'));
                        echo '</td>';
                    echo '</tr>';
                }
                ?>
            </table>

            <!-- menu de paginação -->
            <div style="text-align:center"><?php echo $Feedbacks->getNav(); ?></div>
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
                    echo '"' . $this->Html->getUrl('Feedback', 'lista', array('ordenaPor' => $_GET['ordenaPor'])) . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
                else
                    echo '"' . $this->Html->getUrl('Feedback', 'lista') . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
                ?>
                 , function() {
                    r = true;
                });
            }
        });
    });
</script>
<!-- LazyPHP.com.br -->