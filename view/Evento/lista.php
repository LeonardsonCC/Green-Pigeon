<?php
# Visão view/Evento/lista.php 
/* @var $this EventoController */
/* @var $Eventos Evento[] */
?>
<div class="Usuario container">
    <h4 class="center white-text">Lista de eventos</h4>
    <!-- formulario de pesquisa -->
    <div>
        <form method="get" action="<?php echo $this->Html->getUrl(CONTROLLER, ACTION, array('ordenaPor' => $this->getParam('ordenaPor'))) ?>">
            <div class="input-field">
                <input class="white-text" value="<?php echo $this->getParam('pesquisa') ?>" type="text" name="pesquisa" id="pesquisa">
                <label for="pesquisa">Pesquisar</label>
            </div>
        </form>
    </div>
    <div class="table-responsive responsive-table">
        <table>
            <thead>
                <tr>
                    <th>
                        <a class="white-text" href='<?php echo $this->Html->getUrl('Evento', 'lista', array('ordenaPor' => 'titulo', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                            Nome
                        </a>
                    </th>
                    <th>
                        <a class="white-text" href='<?php echo $this->Html->getUrl('Evento', 'lista', array('ordenaPor' => 'criador_id', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                            Criador
                        </a>
                    </th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($Eventos as $e) {
                    echo '<tr>';
                    echo '<td class="white-text">';
                    echo $this->Html->getLink($e->titulo, 'Evento', 'ver', array($e->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    echo '<td class="white-text">';
                    echo $this->Html->getLink($e->getUsuario()->nome, 'Usuario', 'ver', array($e->getUsuario()->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    $user = Session::get('user');
                    if ($user->adm) {
                        echo '<td width="50">';
                        echo $this->Html->getLink('<i class="material-icons yellow-text">edit</i>', 'Evento', 'editar', array($e->id), array('class' => '', 'title' => 'editar'));
                        echo '</td>';
                        echo '<td width="50">';
                        echo $this->Html->getModalLink('<i class="material-icons red-text">delete</i>', 'Evento', 'apagar', array($e->id), array('class' => 'waves-effect waves-light btn-flat modal-trigger', 'title' => 'apagar'));
                        echo '</td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
        <!-- menu de paginação -->
        <div style="text-align:center"><?php echo $Eventos->getNav(); ?></div>
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
    echo '"' . $this->Html->getUrl('Evento', 'lista', array('ordenaPor' => $_GET['ordenaPor'])) . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
else
    echo '"' . $this->Html->getUrl('Evento', 'lista') . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
?>
                , function () {
                    r = true;
                });
            }
        });
    });
</script>
<!-- LazyPHP.com.br -->