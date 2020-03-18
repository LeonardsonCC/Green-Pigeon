<?php
# Visão view/Dica/lista.php 
/* @var $this DicaController */
/* @var $Dicas Dica[] */
?>
<?php
# Visão view/Usuario/lista.php 
/* @var $this UsuarioController */
/* @var $Usuarios Usuario[] */
?>
<div class="Usuario container">
    <h4 class="center white-text">Lista de Dicas</h4>

    <!-- formulario de pesquisa -->
    <div class="row">
        <div class="col s8 m8 l8">
            <form method="get" action="<?php echo $this->Html->getUrl(CONTROLLER, ACTION, array('ordenaPor' => $this->getParam('ordenaPor'))) ?>">
                <div class="input-field">
                    <input class="white-text" value="<?php echo $this->getParam('pesquisa') ?>" type="text" name="pesquisa" id="pesquisa">
                    <label for="pesquisa">Pesquisar nome</label>
                </div>
            </form>
        </div>
        <div class="col s4 m4 l4">
            <?php echo $this->Html->getLink('<i class="fa fa-plus-circle"></i> Cadastrar Dica', 'Dica', 'cadastrar', NULL, array('class' => 'btn waves-effect waves-light right green', 'style' => 'margin-top: 10px;')); ?>
        </div>
    </div>
    <div class="table-responsive responsive-table">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>
                            <a class="white-text" href='<?php echo $this->Html->getUrl('Dica', 'lista', array('ordenaPor' => 'titulo', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                                Título
                            </a>
                        </th>
                        <th>
                            <a class="white-text" href='<?php echo $this->Html->getUrl('Dica', 'lista', array('ordenaPor' => 'categoria_id', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                                Categoria
                            </a>
                        </th>
                        <th>
                            <a class="white-text" href='<?php echo $this->Html->getUrl('Dica', 'lista', array('ordenaPor' => 'criador_id', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                                Usuário criador
                            </a>
                        </th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <?php
                foreach ($Dicas as $d) {
                    echo '<tr>';
                    echo '<td>';
                    echo $this->Html->getLink($d->titulo, 'Dica', 'ver', array($d->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    echo '<td>';
                    echo $this->Html->getLink($d->getCategoria()->nome, 'Categoria', 'ver', array($d->getCategoria()->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    echo '<td>';
                    echo $this->Html->getLink($d->getUsuario()->email, 'Usuario', 'ver', array($d->getUsuario()->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    echo '<td width="50">';
                    echo $this->Html->getLink('<i class="material-icons yellow-text">edit</i>', 'Dica', 'editar', array($d->id), array('class' => '', 'title' => 'editar'));
                    echo '</td>';
                    echo '<td width="50">';
                    echo $this->Html->getModalLink('<i class="material-icons red-text">delete</i>', 'Dica', 'apagar', array($d->id), array('class' => 'waves-effect waves-light btn-flat modal-trigger', 'title' => 'apagar'));
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
            
            <!-- menu de paginação -->
            <div style="text-align:center"><?php echo $Dicas->getNav(); ?></div>
        </div>
    </div>
</div>

    <script>
        /* faz a pesquisa com ajax */
        $(document).ready(function () {
            $('#pesquisa').keyup(function () {
                var r = true;
                if (r) {
                    r = false;
                    $("div.table-responsive").load(
<?php
if (isset($_GET['ordenaPor']))
    echo '"' . $this->Html->getUrl('Dica', 'lista', array('ordenaPor' => $_GET['ordenaPor'])) . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
else
    echo '"' . $this->Html->getUrl('Dica', 'lista') . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
?>
                    , function () {
                        r = true;
                    });
                }
            });
        });
    </script>
    <!-- LazyPHP.com.br -->
    