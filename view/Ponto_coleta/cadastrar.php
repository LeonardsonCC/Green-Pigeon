<?php
# Visão view/Ponto_coleta/cadastrar.php
/* @var $this Ponto_coletaController */
/* @var $Ponto_coleta Ponto_coleta */
?>
<div class="Dica Ponto_coleta" style="margin-top: 10vh; margin-top: 10vh">
    <div class="row">
        <div class="col s12 m6 l6 offset-m3 offset-l3 form-cadastrar">
            <h3>Cadastrar ponto de coleta</h3>
            <div class="">
                <form method="post" action="<?php echo $this->Html->getUrl('Ponto_coleta', 'cadastrar') ?>"  enctype="multipart/form-data">
                    <?php
                    # nome
                    echo $this->Html->getFormInputWithTooltip('Nome do ponto de coleta', 'nome', $Ponto_coleta->nome, 'text', NULL, true, 4, 'Nome do ponto de coleta seletiva<br>a ser cadastrado');
                    echo '<h6 class="center-align">Selecione o local do ponto de coleta</h6>';
                    echo '
                        <div class="row">
                            <div id="mapa" style="margin-right: 15px; margin-left: 15px">
                                <div id="mapid" class="z-depth-5"></div>
                            </div>
                        </div>';
                    # latitude
                    echo '<div class="col l6">';
                    echo $this->Html->getFormInput('Latitude', 'latitude', $Ponto_coleta->latitude, 'text', ' ', false);
                    echo '</div>';
                    # longitude
                    echo '<div class="col l6">';
                    echo $this->Html->getFormInput('Longitude', 'longitude', $Ponto_coleta->longitude, 'text', ' ', false);
                    echo '</div>';
                    echo '<div class="clearfix"></div>';
                    # descricao
                    echo $this->Html->getFormTextarea('Descrição', 'descricao', $Ponto_coleta->descricao, '', false);
                    # categoria_id
                    if ($this->getParam('categoria_id')) {
                        echo $this->Html->getFormHidden('categoria_id', $this->getParam('categoria_id'));
                    } else {
                        echo $this->Html->getFormSelect('Categoria', 'categoria_id', array_columns((array) $Categorias, 'nome', 'id'));
                    }
                    if ($this->getParam('url_origem')) {
                        echo $this->Html->getFormHidden('url_origem', $this->getParam('url_origem'));
                    }
                    ?>
                    <div class="form-enviar right">
                        <?php
                        if ($this->getParam('url_origem')) {
                            $url_destino = Cript::decript($this->getParam('url_origem'));
                        } else {
                            $url_destino = $this->Html->getUrl('Ponto_coleta', 'lista');
                        }
                        ?>
                        <a href="" class="btn waves-effect waves-green red">Cancelar</a>
                        <button type="submit" class="btn waves-effect waves-red green white-text">salvar</button>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // adionando pontos ao mapa
    var map = L.map('mapid').setView([-28.0237, -48.6139], 8);


    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Green Pigeon'
    }).addTo(map);
    
    var popup = L.popup();
    function onMapClick(e) {
        popup.setLatLng(e.latlng).setContent('<p>Adicionar ponto de coleta aqui?</p><br>Latitude: '+e.latlng.lat+'<br>Longitude: '+e.latlng.lng).openOn(map);
        $('input[name="latitude"]').val(e.latlng.lat);
        $('input[name="longitude"]').val(e.latlng.lng);
    }
    map.on('click', onMapClick);
</script>