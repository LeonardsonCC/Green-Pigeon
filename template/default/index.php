<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Um site para localizar pontos de coleta seletiva próximos.">
        <meta name="author" content="Leonardson Cabral de Carvalho">
        <link rel="icon" type="image/png" href="<?php echo SITE_PATH ?>/template/default/images/gp-icone.png">
        <?php $this->getHeaders(); ?> 

        <link rel="icon" sizes="192x192" href="<?php echo SITE_PATH ?>/template/default/images/gp-icone.png">
        <link rel="apple-touch-icon" href="<?php echo SITE_PATH ?>/template/default/images/gp-icone.png">
        <meta name="msapplication-square310x310logo" content="<?php echo SITE_PATH ?>/template/default/images/gp-icone.png">
        <meta name="theme-color" content="#333">

        <!-- Facebook OG Image -->
        <meta property="og:image" content="https://placid.app/u/za9uz?browser%7Cimage=%24DEFAULT%24&browser%7Curl=%24DEFAULT%24&title=%24DEFAULT%24"/>
        <meta property="og:image:height" content="600"/>
        <meta property="og:image:width" content="1200"/>

        <!-- Twitter Card Image -->
        <meta property="twitter:image" content="https://placid.app/u/za9uz?browser%7Cimage=%24DEFAULT%24&browser%7Curl=%24DEFAULT%24&title=%24DEFAULT%24"/>
        <meta name="twitter:card" content="summary_large_image">

        <script>
            $(document).ready(function() {
                $("[title='Hosted on free web hosting 000webhost.com. Host your own website for FREE.']").hide();
            });
        </script>
    </head>

    <body style="background-image: url('<?php echo SITE_PATH?>/template/default/images/bg.jpg');">
        
        <nav class="green darken-2">
            <div class="nav-wrapper container">
                <?php echo $this->Html->getLink('<img style="height: 62px; margin-top: 1px;" src="' . SITE_PATH . '/template/default/images/gp-icone.png">', 'Index', 'index', null, array('class' => 'brand-logo hide-on-med-and-down'));?>
                <?php echo $this->Html->getLink('<img style="height: 55px; margin-top: 1px;" src="' . SITE_PATH . '/template/default/images/gp-icone.png">', 'Index', 'index', null, array('class' => 'brand-logo left hide-on-med-and-up')); ?>
                <a href="#" class="brand-logo right hide-on-med-and-up"><b>Green Pigeon</b></a>
                <ul class="right hide-on-med-and-down">
                    <?php
                    include 'template/menu.php';
                    ?>
                </ul>
            </div>
        </nav>
        <ul class="mobile">
            <?php
            include 'template/menu-mobile.php'
            ?>
        </ul>

        <div style="min-height: 75vh;">

            <?php
            $this->getContents();
            ?>

        </div>
        <div class="clearfix"></div>
        <!-- /.container -->
        <!-- Modal -->
        <!-- Modal Structure -->
        <div id="modal" class="modal" style="padding: 20px;">
            <div class="modal-content">
                <div class="center">
                    <div class="preloader-wrapper big active">
                        <div class="spinner-layer spinner-blue-only">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div><div class="gap-patch">
                                <div class="circle"></div>
                            </div><div class="circle-clipper right">
                                <div class="circle"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="page-footer green darken-3">
            <div class="container">
                <div class="row">
                    <div class="col l6 s12">
                        <h5 class="white-text">Sobre o projeto</h5>
                        <p class="grey-text text-lighten-4">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Itaque
                            facilis maiores fugiat placeat animi id deserunt ratione rerum ea ipsum quas modi, quisquam iure
                            consequuntur. Quisquam ipsam doloremque assumenda eveniet.</p>
                    </div>
                    <div class="col l4 offset-l2 s12">
                        <h5 class="white-text">Bibliotecas e APIs</h5>
                        <ul>
                            <li><a class="grey-text text-lighten-3" href="#!">Leaflet</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!">Materialize</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!">jQuery</a></li>
                            <li><a class="grey-text text-lighten-3" href="#!">LazyPhp</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <div class="container center">
                    Green Pigeon - 2018
                </div>
            </div>
        </footer>
    </body>
</html>
