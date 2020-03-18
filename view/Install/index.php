<h1><?= __('Bem vindo ao Instalador') ?></h1>
<div class="jumbotron">
    <p>O instalador do <em>lazyphp</em> permite a geração automática dos models, controllers e views a partir da estrutura de sua base de dados, facilitando o início do desenvolvimento de seu sistema.</p>
    <p><strong>Antes de instalar, leia estas recomendações:</strong></p>
    <ul>
        <li>Verifique atentamente o arquivo \config.php;</li>
        <li>Cada tabela do seu BD deve ter uma chave primária única e auto-incremental;</li>
        <li>Verifique as chaves estrangeiras; Eventuais mapeamentos serão gerados a partir delas;</li>
        <li>Embora não seja obrigatório, recomendo que o nome das tabelas esteja no singular;</li>
        <li>Verifique as permissões de escrita nos diretórios;</li>
        <li>Apague o arquivo <em>\controller\InstallController.php</em> após a instalação.</li>
    </ul>

</div>
<div class="panel panel-primary">
    <?php if ($ok) { ?>
        <div class="panel-heading">
            <h3 class="panel-title">Escolha os arquivos que deseja instalar</h3>
        </div>
        <form method='post' role="form">


            <div class="col-md-12" style="margin-top: 25px;">

                <div class="text-right">
                    <input class='btn btn-default btn-sm' type='button' value='Desmarcar todos' onclick='uncheck();'>
                    <input class='btn btn-default btn-sm' type='button' value='Marcar todos' onclick='check();'>
                </div>

                <hr/>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <?php foreach ($tables as $t): ?>
                        <?php
                        $id = "id-" . $t->name;
                        ?>


                        <div class="panel panel-default">
                            <div class="panel-heading" >
                                <h4 class="panel-title" style="color:black">
                                    <button data-toggle="collapse" data-parent="#accordion" 
                                            autofocus="" href="#<?php echo $id; ?>" 
                                            class="btn btn-xs btn-default" 
                                            type="button">
                                        <span class="glyphicon glyphicon-chevron-down"></span>
                                    </button>

                                    <?php echo $t->name; ?>

                                    <div class="pull-right">
                                        <label><input type="checkbox" class="principal"
                                                      name="model[<?php echo $t->name; ?>]"
                                                      id="check_model_<?php echo $t->name; ?>" 
                                                      checked/>
                                            <small style="margin-right: 15px;">Modelo</small>
                                        </label>
                                        <label><input type="checkbox" class="principal"
                                                      name="controller[<?php echo $t->name; ?>]"
                                                      id="check_controller_<?php echo $t->name; ?>" 
                                                      checked/>
                                            <small style="margin-right: 15px;">Controlador</small>
                                        </label>
                                        <label><input type="checkbox" class="principal"
                                                      name="view[<?php echo $t->name; ?>]"
                                                      id="check_view_<?php echo $t->name; ?>" 
                                                      checked/>
                                            <small style="margin-right: 15px;">Visão</small>
                                        </label>
                                        <label><input type="checkbox" class="principal"
                                                      name="menus[<?php echo $t->name; ?>]"
                                                      id="check_menu_<?php echo $t->name; ?>" 
                                                      checked/>
                                            <small style="margin-right: 15px;">Menu</small>
                                        </label>
                                    </div>
                                </h4>
                            </div>
                            <div id="<?php echo $id; ?>" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="panel-group" id="configs-acordion<?php echo $id; ?>" role="tablist" aria-multiselectable="true">
                                        <?php include "view/Install/config-cadastro.php"; ?>
                                        <?php //include "view/Install/config-titulos.php"; ?>
                                        <?php include "view/Install/visao_listar.php"; ?>
                                        <?php include "view/Install/visao_ver.php"; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            $('#check_view_<?php echo $t->name; ?>').change(function () {
                                if ($('#check_view_<?php echo $t->name; ?>').is(':checked')) {
                                    $('#rotulo_visoes_<?php echo $t->name; ?>').show(100);
                                    $('#ConfigVisaoListar_<?php echo $t->name; ?>').show(100);
                                    $('#ConfigVisaoVer_<?php echo $t->name; ?>').show(100);
                                }
                                else {
                                    $('#rotulo_visoes_<?php echo $t->name; ?>').hide(100);
                                    $('#ConfigVisaoListar_<?php echo $t->name; ?>').hide(100);
                                    $('#ConfigVisaoVer_<?php echo $t->name; ?>').hide(100);
                                }
                            });

                            $('#check_menu_<?php echo $t->name; ?>').change(function () {
                                if ($('#check_menu_<?php echo $t->name; ?>').is(':checked')) {
                                    $('#rotulo_menu_<?php echo $t->name; ?>').show(100);
                                }
                                else {
                                    $('#rotulo_menu_<?php echo $t->name; ?>').hide(100);
                                }
                            });
                        </script>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="text-right col-md-12">
                <h4>Menu de Navegação</h4>

                <div class="clearfix"></div>
                <div class="checkbox pull-right">
                    <label>
                        <input type="checkbox" name="menu" checked>
                        <small>(Re)Instalar menu</small>
                    </label>
                </div>
                <div class="clearfix"></div>
                <h4>Sobrescrever Modelos, Visões e Controladores caso existam?</h4>

                <div class="clearfix"></div>
                <div class="checkbox pull-right">
                    <label>
                        <input type="checkbox" name="sobrescrever">
                        <small>sobrescrever?</small>
                    </label>
                </div>
                <div class="clearfix"></div>
            </div>


            <div class="clearfix"></div>


            <div class="panel-footer">
                <input type="submit" value="<?= __('Instalar'); ?>" class="btn btn-primary pull-right">
                <br class="clearfix"><br class="clearfix">
            </div>
        </form>
    <?php } ?>
</div>
<script>
    function check() {
        $('.principal').prop('checked', true);
<?php foreach ($tables as $t): ?>
            $('#rotulo_visoes_<?php echo $t->name; ?>').show(100);
            $('#ConfigVisaoListar_<?php echo $t->name; ?>').show(100);
            $('#ConfigVisaoVer_<?php echo $t->name; ?>').show(100);
            $('#rotulo_menu_<?php echo $t->name; ?>').show(100);
<?php endforeach; ?>
    }
    function uncheck() {
        $('.principal').prop('checked', false);
<?php foreach ($tables as $t): ?>
            $('#rotulo_visoes_<?php echo $t->name; ?>').hide(100);
            $('#ConfigVisaoListar_<?php echo $t->name; ?>').hide(100);
            $('#ConfigVisaoVer_<?php echo $t->name; ?>').hide(100);
            $('#rotulo_menu_<?php echo $t->name; ?>').hide(100);
<?php endforeach; ?>
    }

</script>
