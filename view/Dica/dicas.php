<?php
# Visão view/Dica/dicas.php 
/* @var $this DicaController */
/* @var $Dica Dica */
/* @var $Categoria Categoria */
/* @var $Categorias Categoria[] */
?>
<div class="container" style="margin-bottom: 70px;">
    <div class="row">
        <!-- Titulo -->
        <h1 class="col s12 m12 l12 xl12 center-align white-text titulo">Dicas</h1>
    </div>
    <div class="row mapa-pagina">
        <div class=" col l12 m12 janela-dicas">
            <!-- Categorias -->
            <div class="col m4 l4 hide-on-med-and-down">
                <ul>
                    <li>
                        <h4 class="white-text center">
                            Categorias
                        </h4>
                    </li>

                    <!-- Lista de categorias -->

                    <?php
                    foreach ($Categorias as $c) {
                        if ($c->getDicas()->count() > 0) {
                            echo '<li class="cat-itens">';
                            echo $this->Html->getLink($c->nome, 'Dica', 'dicas', array($c->id), NULL);
                            echo '</li>';
                        }
                    }
                    ?>
                </ul>
            </div>
            <div class="col s12 l8 m8 white-text conteudo-dicas">



                <!-- se possuir apenas uma dica -->
                <?php
                if ( isset($Dica)) {
                    echo '<div class="col s9 m9 l9">';
                    echo '<h3>';
                    echo '<!-- Titulo -->';
                    echo $Dica->titulo;
                    echo '</h3>';
                    echo '<small>';
                    echo '<!-- Autor da dica -->';
                    echo $Dica->getUsuario()->nome . ' - ';
                    $date = date_create($Dica->data_criacao);
                    echo date_format($date, 'd/m/Y');
                    echo '</small>';
                    echo '</div>';
                    echo ' <div class="right icone-dicas">';
                    echo '<a href="mapa.html#cat">';
                    echo '<!-- ícone da categoria -->';
                    echo '<img src="' . SITE_PATH . '/template/default/images/icones/marker-icon-2x.png">';
                    echo '</a>';
                    echo '</div>';
                    echo '<div class="clearfix"></div>';
                    echo '<div class="hr"></div>';
                    echo '<div class="col m12 l12" style="margin-bottom: 30px;">';
                    echo '<!-- Texto da dica -->';
                    echo $Dica->texto;
                    echo '</div>';
                }
                else{
                echo '<div class="col s12 m12 l12">';
                echo '<h3>';
                echo '<!-- Titulo -->';
                echo 'Dicas da categoria ' . $Categoria->nome;
                echo '</h3>';
                echo '</div>';
                echo '<div class="row">';
                if($Dicas){
                    foreach ($Dicas as $d) {
                        echo '<div class="col s12 m6">';
                        echo '<div class="card blue-grey darken-1 small">';
                        echo '<div class="card-content white-text">';
                        #Titulo da dica
                        echo '<span class="card-title">' . $d->titulo . '</span>';
                        echo '<hr>';
                        #Texto da dica
                        echo '<p>'.$d->texto.'</p>';
                        echo '<div class="card-action blue-grey darken-1">';
                        echo '<a href="' . $this->Html->getUrl('Dica', 'dicas', array($Categoria->id . '/' . $d->id)) . '" class="white-text">Ler dica</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<h3>Esta categoria não possui dicas :(</h3>';
                }
                echo '</div>';
                }
                ?>

            </div>
        </div>
    </div>

    <div class="fixed-action-btn hide-on-med-and-up">
        <a data-target="slide-out" class="btn-floating btn-large green darken-2 sidenav-trigger">
            <i class="large material-icons">menu</i>
        </a>
    </div>
    <ul id="slide-out" class="sidenav dicas-sidenav">
        <li>
            <h4 class="white-text center">
                Categorias
            </h4>
        </li>
<?php
foreach ($Categorias as $c) {
    echo '<li class="cat-itens">';
    echo $this->Html->getLink($c->nome, 'Dica', 'dicas', array($c->id), NULL);
    echo '</li>';
}
?>
    </ul>
</div>


<script>
    /* faz a pesquisa com ajax */

    $(document).ready(function () {
        $('.sidenav').sidenav();
        var elem = document.querySelector('.sidenav');
        var instance = M.Sidenav.init(elem, {
            onOpenStart: function () {
                $('.mobile').slideUp();
            },
            onCloseEnd: function () {
                $('.mobile').slideDown();
            }
        });
        $('#pesquisa').keyup(function () {
            var r = true;
            if (r) {
                r = false;
                $("div.table-responsive").load(
<?php
if (isset($_GET['ordenaPor']))
    echo '"' . $this->Html->getUrl('Dica', 'dicas', array('ordenaPor' => $_GET['ordenaPor'])) . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
else
    echo '"' . $this->Html->getUrl('Dica', 'dicas') . 'pesquisa:" + encodeURIComponent($("#pesquisa").val()) + " .table-responsive"';
?>
                , function () {
                    r = true;
                });
            }
        });
    });
</script>
<!-- LazyPHP.com.br -->