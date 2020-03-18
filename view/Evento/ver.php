<?php
# Visão view/Evento/ver.php 
/* @var $this EventoController */
/* @var $Evento Evento */
?>
<div class="container form-ver ">
    <div class="row">
        <div class="col s12 m12 l12">
            <h3 class="center-align"><?php echo $Evento->titulo ?></h3>
            <?php
            $data = date_create($Evento->data_evento);
            $data = date_format($data, 'd/m/Y');
            ?>
            <small class="right"><?php echo $Evento->getUsuario()->nome . ' - ' . $data ?></small>
        </div>
    </div>
    <div class="row" style="padding: 20px;">
        <div>
            <p>
                <?php echo $Evento->texto ?>
            </p>
        </div>
    </div>
    <?php
    if($Evento->latitude && $Evento->longitude){
        echo '
            <div class="row hide-on-med-and-down">
                <div class="col l8 offset-l2" id="mapid"></div>
            </div>
            ';
    }
    ?>
</div>
<!-- LazyPHP.com.br -->
<script>
    

<?php
if( $Evento->latitude && $Evento->longitude ){
    echo '
        // adionando pontos ao mapa
        var map = L.map(\'mapid\').setView([-28.0237, -48.6139], 8);


        L.tileLayer(\'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png\', {
            attribution: \'Green Pigeon\'
        }).addTo(map);
        L.tileLayer.provider(\'HERE.normalDay\').addTo(map);
        '; 
    echo 'L.marker([' . $Evento->latitude . ', ' . $Evento->longitude . ']).addTo(map)';
    echo '.bindPopup("';
    echo '<h6>Local do evento</h6>';
    echo '");';
}
?>
</script>