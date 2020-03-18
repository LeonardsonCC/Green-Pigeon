<?php
# Visão view/Ponto_coleta/mapa.php 
/* @var $this Ponto_coletaController */
/* @var $Ponto_coletas Ponto_coleta[] */
?>
<style>
.icon-block {
  padding: 0 15px;
}
.icon-block .material-icons {
	font-size: inherit;
}
</style>
<div class="container" style="margin-bottom: 70px;">
    <div class="row">
        <!-- Titulo -->
        <h1 class="col s12 m12 l12 xl12 center-align white-text titulo">Pontos de Coleta Seletiva</h1>
    </div>

    <!-- Conteudo vai aqui -->
    <div class="mapa-pagina">
        <div class="row">
            <h3 class="white-text center">Mapa para pontos de coleta</h3>
        </div>
        <div class="row">
            <div id="mapa">
                <div id="mapid" class="z-depth-5"></div>
            </div>
        </div>
        <div class="row white-text texto-mapa">
            <div class="row">
                <div class="col s12 m6 offset-m3">
                    <div class="icon-block">
                        <h2 class="center"><i class="material-icons">place</i></h2>
                        <h4 class="center">Encontre!</h4>

                        <p class="light center">No mapa mostra todos os pontos de coleta seletiva já cadastrados no sistema, encontre o mais próximo a você. Permita a localização para que seja mostrado no mapa!</p>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col s12 m4">
                    <div class="icon-block">
                        <h2 class="center"><i class="material-icons">person_add</i></h2>
                        <h4 class="center">Cadastre-se</h4>

                        <p class="light center">Crie uma conta para cadastrar pontos de coleta seletiva conhecidos por você, no mapa, clicando <a href="<?php echo $this->Html->getUrl('Login', 'login') ?>">aqui</a>!</p>
                    </div>
                </div>
                <div class="col s12 m4">
                    <div class="icon-block">
                        <h2 class="center"><i class="material-icons">map</i></h2>
                        <h4 class="center">Cadastre um ponto</h4>

                        <p class="light center">Um novo ícone vermelho surgirá no canto inferior direito, com a opção de cadastro. Ou clique <a href="<?php echo $this->Html->getUrl('Ponto_coleta', 'cadastrar') ?>">aqui</a> se você já estiver em sua conta</p>
                    </div>
                </div>
                <div class="col s12 m4">
                    <div class="icon-block">
                        <h2 class="center"><i class="material-icons">add_location</i></h2>
                        <h4 class="center">Preencha os campos</h4>

                        <p class="light center">Pronto! Seu ponto de coleta seletiva já está cadastrado no mapa</p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
<?php
if (Session::get('user')) {
    echo '<div class="fixed-action-btn" >
            <a class="btn-floating btn-large red tooltipped" data-position="left" data-tooltip="Menu">
              <i class="large material-icons">menu</i>
            </a>
            <ul>
                <li><a class="btn-floating green modal-trigger tooltipped" data-position="left" data-tooltip="Adicionar ponto de coleta" href="#modal-confirma" data-href="">
                    <i class="material-icons">add</i>
                </a></li>
                <li><a class="btn-floating blue tooltipped" data-position="left" href="' . $this->Html->getUrl('Voto', 'cadastrar') . '" data-tooltip="Aprovar/desaprovar pontos de coleta">
                    <i class="material-icons">where_to_vote</i>
                </a></li>
            </ul>
          </div>';
    echo '<div id="modal-confirma" class="modal">
            <div class="modal-content">
                <h3>Adicionar ponto de coleta</h3>                
                <p>Colabore com a comunidade adicionando os pontos de coleta seletiva que você conhece!</p>
                <div class="right">
                    <a href="#" class="modal-close btn red">Cancelar</a>
                    <a href="' . $this->Html->getUrl('Ponto_coleta', 'cadastrar') . '" class="btn green">Continuar</a>
                </div>
                <div class="clearfix"></div>
            </div>
          </div>';
}
?>
<script>
    // adionando pontos ao mapa
    var map = L.map('mapid').setView([-28.0237, -48.6139], 8);

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 18,
        attribution: 'GreenPigeon',
        id: 'mapbox.streets'
    }).addTo(map);

    function onLocationFound(e) {
        console.log('Local do usuário no mapa');
        M.toast({html: 'Sua localização está no mapa', classes: 'green white-text'});
    }

    function onLocationError(e) {
        console.log('Usuário não permitiu');
        M.toast({html: 'Localização não permitida', classes: 'red white-text'});
    }

    map.on('locationfound', onLocationFound);
    map.on('locationerror', onLocationError);

    map.locate({setView: true, maxZoom: 20});
    
<?php
foreach ($Ponto_coletas as $pc) {
    $exibir = '';
    if(!$pc->exibir) {
        $exibir = '<span class=\"red-text\">Ponto não verificado</span><br>';
    }
    echo 'L.marker([' . $pc->latitude . ', ' . $pc->longitude . ']).addTo(map)';
    echo '.bindPopup("';
    echo '<h6>' . $pc->nome . '</h6>'.$exibir.'<p>' . substr($pc->descricao, 0, 150) . '</p>';
    echo '");';
}
?>
</script>