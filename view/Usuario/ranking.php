<?php
# Visão view/Usuario/lista.php 
/* @var $this UsuarioController */
/* @var $Usuarios Usuario[] */
?>
<div class="Usuario container">
    <h4 class="center white-text">Ranking de colaborações</h4>
    <!-- formulario de pesquisa -->
    <div>
        <form method="get" action="<?php echo $this->Html->getUrl(CONTROLLER, ACTION, array('ordenaPor' => $this->getParam('ordenaPor'))) ?>">
            <div class="input-field">
                <input class="white-text" value="<?php echo $this->getParam('pesquisa') ?>" type="text" name="pesquisa" id="pesquisa">
                <label for="pesquisa">Pesquisar email</label>
            </div>
        </form>
    </div>
    <div class="table-responsive responsive-table">
        <table>
            <thead>
                <tr>
                    <th>
                        <a class="white-text" href='<?php echo $this->Html->getUrl('Usuario', 'lista', array('ordenaPor' => 'email', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                            
                        </a>
                    </th>
                    <th>
                        <a class="white-text" href='<?php echo $this->Html->getUrl('Usuario', 'lista', array('ordenaPor' => 'email', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                            Email
                        </a>
                    </th>
                    <th>
                        <a class="white-text" href='<?php echo $this->Html->getUrl('Usuario', 'lista', array('ordenaPor' => 'nome', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                            Nome
                        </a>
                    </th>
                    <th>
                        <a class="white-text" href='<?php echo $this->Html->getUrl('Usuario', 'lista', array('ordenaPor' => 'pontuacao', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                            Pontuação
                        </a>
                    </th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($Usuarios as $u) {
                    echo '<tr>';
                    echo '<td class="white-text">';
                    echo $this->Html->getLink($i, 'Usuario', 'ver', array($u->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    echo '<td class="white-text">';
                    if ($u->adm == 2) {
                        $prefix = '<span style="font-weight: 900" class="red-text">[ADM]</span>   ';
                    } else {
                        $prefix = '';
                    }
                    echo $this->Html->getLink($prefix . $u->email, 'Usuario', 'ver', array($u->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    echo '<td class="white-text">';
                    echo $this->Html->getLink($u->nome, 'Usuario', 'ver', array($u->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    echo '<td>';
                    echo $this->Html->getLink($u->pontuacao, 'Usuario', 'ver', array($u->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    echo '<td width="50">';
                    echo $this->Html->getLink('<i class="material-icons yellow-text">edit</i>', 'Usuario', 'editar', array($u->id), array('class' => '', 'title' => 'editar'));
                    echo '</td>';
                    echo '<td width="50">';
                    echo $this->Html->getModalLink('<i class="material-icons red-text">delete</i>', 'Usuario', 'apagar', array($u->id), array('class' => 'waves-effect waves-light btn-flat modal-trigger', 'title' => 'apagar'));
                    echo '</td>';
                    echo '</tr>';
                    $i++;
                }
                ?>
            </tbody>
        </table>
        <!-- menu de paginação -->
        <div style="text-align:center"><?php echo $Usuarios->getNav(); ?></div>
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
    echo '"' . $this->Html->getUrl('Usuario', 'ranking', array('ordenaPor' => $_GET['ordenaPor'])) . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
else
    echo '"' . $this->Html->getUrl('Usuario', 'ranking') . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
?>
                , function () {
                    r = true;
                });
            }
        });
    });
</script>
<!-- LazyPHP.com.br -->