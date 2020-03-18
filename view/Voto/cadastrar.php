<?php
# Visão view/Voto/cadastrar.php
/* @var $this VotoController */
/* @var $Voto Voto */
/* @var $this Ponto_coletaController */
/* @var $Votos Voto[] */
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
        <div class="row">
            <?php
            foreach ($Ponto_coletas as $pc) {
                echo '<div class="col s12 m4">';
                echo '<div class="card">';
                echo '<div class="card-image">';
                echo '<div id="' . $this->preparaUrl($pc->nome) . '" style="height: 250px; width: auto; margin: 0 auto;"></div>';
                echo '<span class="card-title">' . $pc->nome . '</span>';
                echo '</div>';
                echo '<div class="card-content">';
                echo '<p>' . $pc->descricao . '</p>';
                echo '</div>';
                echo '<div class="card-action white">';
//            echo $this->Html->getModalLink('Votar', 'Voto', 'votar', array($pc->id), array('class' => 'modal-trigger'));;
                $link = false;
                foreach ($Votos as $v) {
                    if ($pc->id == $v->ponto_id) {
                        if ($v->valor) {
                            echo '<span class="green-text"> APROVADO! </span>';
                        } else {
                            echo '<span class="red-text"> DESAPROVADO! </span>';
                        }
                        $link = true;
                        break;
                    }
                    if (end($Votos) == $v && !$link) {
                        echo $this->Html->getModalLink('Votar!', 'Voto', 'votar', array($pc->id), array('class' => 'modal-trigger blue-text lighten-2-text'));
                        $link = true;
                    }
                }
                if (!$link) {
                    echo $this->Html->getModalLink('Votar!', 'Voto', 'votar', array($pc->id), array('class' => 'modal-trigger blue-text lighten-2-text'));
                    $link = true;
                }
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '<script>
            // instantiate map
            var ' . $this->preparaUrl($pc->nome) . ' = L.map(\'' . $this->preparaUrl($pc->nome) . '\', {
            }).setView([' . $pc->latitude . ', ' . $pc->longitude . '], 8);
            // add a marker
            L.tileLayer(\'http://{s}.tile.osm.org/{z}/{x}/{y}.png\', {
                attribution: \'&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors\'
            }).addTo(' . $this->preparaUrl($pc->nome) . ');
            var marker = L.marker([' . $pc->latitude . ', ' . $pc->longitude . ']).addTo(' . $this->preparaUrl($pc->nome) . ');
            marker.bindPopup("Here is a marker.  Click to link to another site");
        
            </script>';
            }
            ?>
        </div>

        <!-- menu de paginação -->
        <div style="text-align:center"><?php echo $Ponto_coletas->getNav(); ?></div>
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
    echo '"' . $this->Html->getUrl('Ponto_coleta', 'lista', array('ordenaPor' => $_GET['ordenaPor'])) . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
else
    echo '"' . $this->Html->getUrl('Ponto_coleta', 'lista') . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
?>
                , function () {
                    r = true;
                });
            }
        });
    });

</script>