<?php
# Visão view/Evento/cadastrar.php
/* @var $this EventoController */
/* @var $Evento Evento */
?>
<div class="Evento container" style="margin-top: 10vh; margin-top: 10vh">
    <div class="row">
        <div class="col s12 m6 l6 offset-m3 offset-l3 form-cadastrar">
            <h3>Cadastrar evento</h3>
            <div class="">
                <form method="post" action="<?php echo $this->Html->getUrl('Evento', 'cadastrar') ?>"  enctype="multipart/form-data">
                    <?php
                    # titulo
                    echo $this->Html->getFormInput('Titulo', 'titulo', $Evento->titulo, 'text', '', true);
                    #capa
                    echo $this->Html->getFormInput('Capa do evento', 'capa', '', 'file', '', true);
                    # data_evento
                    echo $this->Html->getFormInput('Data do evento', 'data_evento', '', 'date', '', true);
                    # texto
                    echo $this->Html->getFormTextareaHtml('Texto', 'texto', $Evento->texto, 'texto', true);
                    #Local
                    echo '<br>';
                    echo '<h6 class="center-align">Selecione o local do ponto de coleta</h6>';
                    echo '
                        <div class="row">
                            <div id="mapa" style="margin-right: 15px; margin-left: 15px">
                                <div id="mapid" class="z-depth-5"></div>
                            </div>
                        </div>';
                    # latitude
                    echo '<div class="col l6">';
                    echo $this->Html->getFormInput('Latitude', 'latitude', $Evento->latitude, 'text', ' ', false);
                    echo '</div>';
                    # longitude
                    echo '<div class="col l6">';
                    echo $this->Html->getFormInput('Longitude', 'longitude', $Evento->longitude, 'text', ' ', false);
                    echo '</div>';
                    echo '<div class="clearfix"></div>';
                    if ($this->getParam('url_origem')) {
                        echo $this->Html->getFormHidden('url_origem', $this->getParam('url_origem'));
                    }
                    ?>
                    <div class="clearfix"></div>
                    <div class = "form-enviar right">
                        <?php
                        if ($this->getParam('url_origem')) {
                            $url_destino = Cript::decript($this->getParam('url_origem'));
                        } else {
                            $url_destino = $this->Html->getUrl('Evento', 'lista');
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
<script>
    // adionando pontos ao mapa
    var map = L.map('mapid').setView([ - 28.0237, - 48.6139], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Green Pigeon'
    }).addTo(map);
    var popup = L.popup();
    function onMapClick(e) {
    popup.setLatLng(e.latlng).setContent('<p>Adicionar local do evento</p><br>Latitude: ' + e.latlng.lat + '<br>Longitude: ' + e.latlng.lng).openOn(map);
    $('input[name="latitude"]').val(e.latlng.lat);
    $('input[name="longitude"]').val(e.latlng.lng);
    }
    map.on('click', onMapClick);
</script>