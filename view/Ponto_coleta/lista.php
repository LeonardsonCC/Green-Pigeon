<?php
# Visão view/Ponto_coleta/lista.php 
/* @var $this Ponto_coletaController */
/* @var $Ponto_coletas Ponto_coleta[] */
?>
<div class="Usuario container">
    <h4 class="center white-text">Lista de pontos de coleta</h4>
    <!-- formulario de pesquisa -->
    <div>
        <form method="get" action="<?php echo $this->Html->getUrl(CONTROLLER, ACTION, array('ordenaPor' => $this->getParam('ordenaPor'))) ?>">
            <div class="input-field">
                <input class="white-text" value="<?php echo $this->getParam('pesquisa') ?>" type="text" name="pesquisa" id="pesquisa">
                <label for="pesquisa">Pesquisar ponto de coleta</label>
            </div>
        </form>
    </div>
    <div class="table-responsive responsive-table">
        <table>
            <thead>
                <tr>
                    <th>
                        <a class="white-text" href='<?php echo $this->Html->getUrl('Usuario', 'lista', array('ordenaPor' => 'email', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                            Nome
                        </a>
                    </th>
                    <th>
                        <a class="white-text" href='<?php echo $this->Html->getUrl('Usuario', 'lista', array('ordenaPor' => 'nome', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                            Latitude
                        </a>
                    </th>
                    <th>
                        <a class="white-text" href='<?php echo $this->Html->getUrl('Usuario', 'lista', array('ordenaPor' => 'pontuacao', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                            Longitude
                        </a>
                    </th>
                    <th>
                        <a class="white-text" href='<?php echo $this->Html->getUrl('Usuario', 'lista', array('ordenaPor' => 'pontuacao', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                            É exibido?
                        </a>
                    </th>
                    <th>
                        <a class="white-text" href='<?php echo $this->Html->getUrl('Usuario', 'lista', array('ordenaPor' => 'pontuacao', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                            Aprovações
                        </a>
                    </th>
                    <th>
                        <a class="white-text" href='<?php echo $this->Html->getUrl('Usuario', 'lista', array('ordenaPor' => 'pontuacao', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                            Desaprovações
                        </a>
                    </th>
                    <th>
                        <a class="white-text" href='<?php echo $this->Html->getUrl('Usuario', 'lista', array('ordenaPor' => 'pontuacao', 'pesquisa' => $this->getParam('pesquisa'))); ?>'>
                            Criador
                        </a>
                    </th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($Ponto_coletas as $p) {
                    echo '<tr>';
                    echo '<td class="white-text">';
                    echo $this->Html->getLink($p->nome, 'Ponto_coleta', 'ver', array($p->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    echo '<td class="white-text">';
                    echo $this->Html->getLink($p->latitude, 'Ponto_coleta', 'ver', array($p->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    echo '<td class="white-text">';
                    echo $this->Html->getLink($p->longitude, 'Ponto_coleta', 'ver', array($p->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    echo '<td>';
                    if($p->exibir){
                        $exibido = 'Sim';
                    }
                    else{
                        $exibido = 'Não';
                    }
                    echo $this->Html->getLink($exibido, 'Usuario', 'ver', array($u->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    echo '<td class="white-text">';
                    echo $this->Html->getLink($p->aprovacoes, 'Ponto_coleta', 'ver', array($p->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    echo '<td class="white-text">';
                    echo $this->Html->getLink($p->desaprovacoes, 'Ponto_coleta', 'ver', array($p->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    echo '<td class="white-text">';
                    echo $this->Html->getLink($p->getUsuario()->nome, 'Usuario', 'ver', array($p->getUsuario()->id), // variaveis via GET opcionais
                            array('class' => 'white-text')); // atributos HTML opcionais
                    echo '</td>';
                    echo '<td width="50">';
                    echo $this->Html->getLink('<i class="material-icons yellow-text">edit</i>', 'Ponto_coleta', 'editar', array($p->id), array('class' => '', 'title' => 'editar'));
                    echo '</td>';
                    echo '<td width="50">';
                    echo $this->Html->getModalLink('<i class="material-icons red-text">delete</i>', 'Ponto_coleta', 'apagar', array($p->id), array('class' => 'waves-effect waves-light btn-flat modal-trigger', 'title' => 'apagar'));
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <!-- menu de paginação -->
        <div style="text-align:center"><?php echo $Ponto_coletas->getNav(); ?></div>
    </div>
</div>

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
                    echo '"' . $this->Html->getUrl('Ponto_coleta', 'lista', array('ordenaPor' => $_GET['ordenaPor'])) . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
                else
                    echo '"' . $this->Html->getUrl('Ponto_coleta', 'lista') . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
                ?>
                 , function() {
                    r = true;
                });
            }
        });
    });
</script>
<!-- LazyPHP.com.br -->