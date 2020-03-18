<div class="panel panel-info">
    <div class="panel-heading" data-toggle="collapse" data-parent="#configs-acordion<?php echo $id; ?>"
         href="#collapseTitulos_<?php echo $t->name; ?>">
        <h4 class="panel-title" style="cursor: pointer">
            <span class="glyphicon glyphicon-cog"></span>
            Configurações de Rótulos
        </h4>
    </div>
    <div id="collapseTitulos_<?php echo $t->name; ?>" class="panel-collapse collapse">

        <div class="panel-body">
            <div class="col-md-12">
                <label>Nome do módulo: <strong><?php echo ucfirst($t->name); ?></strong></label>
            </div>

            <hr/>
            <div id="rotulo_menu_<?php echo $t->name; ?>">
                <h4>Menu</h4>
                <div class="col-md-3">
                    <label>Nome no menu</label>
                    <input type="text" class="form-control"
                           name="modTitle_menu_<?php echo $t->name; ?>"
                           value="<?php echo $this->getPlural(ucfirst($t->name)); ?>"/>
                </div>
                <div class="clearfix"></div>
            </div>
            <hr/>
            <div id="rotulo_visoes_<?php echo $t->name; ?>">
                <h4>Botões</h4>
                <div class="col-md-3">
                    <label>Botao de cadastro</label>
                    <input type="text" class="form-control"
                           name="btn_add_<?php echo $t->name; ?>"
                           value="Cadastrar <?php echo $t->name; ?>"/>
                </div>
                <div class="clearfix"></div>
                <hr/>
                <h4>Títulos das Páginas</h4>
                <div class="col-md-3">
                    <label>Titulo da tela de listagem</label>
                    <input type="text" class="form-control"
                           name="modTitle_all_<?php echo $t->name; ?>"
                           value="<?php echo $this->getPlural(ucfirst($t->name)); ?>"/>
                </div>

                <div class="col-md-3">
                    <label>Titulo da tela de cadastro</label>
                    <input type="text" class="form-control"
                           name="modTitle_add_<?php echo $t->name; ?>"
                           value="<?php echo "Cadastro de " . ucfirst($t->name); ?>"/>
                </div>

                <div class="col-md-3">
                    <label>Titulo da tela de edição</label>
                    <input type="text" class="form-control"
                           name="modTitle_edit_<?php echo $t->name; ?>"
                           value="<?php echo "Edição de " . ucfirst($t->name); ?>"/>
                </div>

                <div class="col-md-3">
                    <label>Titulo  da tela de visualização</label>
                    <input type="text" class="form-control"
                           name="modTitle_view_<?php echo $t->name; ?>"
                           value="<?php echo 'Ver ' . ucfirst($t->name); ?>"/>
                </div>


                <div class="clearfix"></div>
                <hr>
                <h4>Nomes dos campos</h4>
                <p>Nomes dos atributos nos labels dos formulários e nas listagens</p>
                <table class="table table-striped">
                    <tr>
                        <th>Coluna</th>
                        <th>Rótulo</th>
                    </tr>

                    <?php
                    $tbn = $t->name;
                    $tableSchema = $this->getTableSchema($t->name);
                    foreach ($tableSchema as $row):
                        $name = $row->Field;
                        foreach ($dbschema as $dba) {
                            if ($t->name == $dba->table) {
                                if ($row->Field == $dba->fk) {
                                    $name = ucfirst($dba->reftable);
                                    break;
                                }
                            }
                        }
                        ?>
                        <tr>                            
                            <td><?php echo $row->Field; ?></td>
                            <td>
                                <input
                                    name="colunasTitle_<?php echo $t->name; ?>_<?php echo $row->Field; ?>]"
                                    value="<?php echo $name; ?>" type="text"
                                    class="form-control"/>
                            </td>                            
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <br/>
            <div class="clearfix"></div>
        </div>
    </div>
</div>