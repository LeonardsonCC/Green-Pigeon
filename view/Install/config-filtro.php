<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#configs-acordion"
         href="#collapseFiltros_<?php echo $t->name; ?>">
        <h4 class="panel-title">Configurações de Filtros</h4>
    </div>
    <div id="collapseFiltros_<?php echo $t->name; ?>" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="col-md-12">
                <table class="table table-striped">
                    <tr>
                        <th></th>
                        <th>Filtros</th>
                        <th>Titulo do Filtro</th>
                        <th>Coluna de referência</th>
                    </tr>

                    <?php
                    $tbn = $t->name;
                    foreach ($tables_cols->$tbn as $row): ?>
                        <tr>
                            <td><?php echo $row->apresentacao; ?></td>
                            <td>
                                <input
                                    name="filtros[<?php echo $t->name; ?>][<?php echo $row->coluna; ?>][0]"
                                    value="<?php echo $row->coluna; ?>" type="checkbox"/>
                            </td>
                            <td>
                                <input
                                    name="filtros[<?php echo $t->name; ?>][<?php echo $row->coluna; ?>][1]"
                                    value="<?php echo $row->coluna; ?>" type="text"
                                    class="form-control"/>
                            </td>
                            <td>
                                <?php
                                foreach (InstallController::relacaoDB() as $dba) {
                                    if ($t->name == $dba->table) {
                                        if ($row->coluna == $dba->fk) {
                                            echo '<select class="form-control" name="filtros[' . $t->name . '][' . $row->coluna . '][2]">';
                                            foreach (InstallController::relacaoTB($dba->reftable) as $tbl) {
                                                echo '<option value="' . $tbl->Field . '">' . $tbl->Field . '</option>';
                                            }
                                            echo "</select>";
                                        }
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>