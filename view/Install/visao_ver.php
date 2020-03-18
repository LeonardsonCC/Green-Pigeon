<div class="panel panel-info" id="ConfigVisaoVer_<?php echo $t->name; ?>">
    <div class="panel-heading" data-toggle="collapse" data-parent="#configs-acordion<?php echo $id; ?>"
         href="#collapseVisaoVer_<?php echo $t->name; ?>">
        <h4 class="panel-title" style="cursor: pointer">
            <span class="glyphicon glyphicon-cog"></span>
            Configurar Visão <strong>ver</strong>
        </h4>
    </div>
    <div id="collapseVisaoVer_<?php echo $t->name; ?>" class="panel-collapse collapse">
        <div class="panel-body">

            <div class="col-md-12">
                <h4>Selecione o que deve aparecer na visualização de um registro</h4>
                <hr/>

                <table class="table table-striped">
                    <tr>
                        <th>Exibir?</th>
                        <th>Atributo</th>
                        <th>Coluna vinculada</th>
                    </tr>

                    <?php
                    $table = $t->name;
                    $tableSchema = $this->getTableSchema($t->name);
                    foreach ($tableSchema as $row):
                        $priField = NULL;
                        if ($row->Key == 'PRI') {
                            $priField = $row->Field;
                        }
                        ?>
                        <tr>
                            <td>
                                <?php $checked = $priField != $row->Field ? 'checked' : ''; ?>
                                <input name="colunasVer_<?php echo $t->name; ?>[]"
                                       value="<?php echo $row->Field; ?>" type="checkbox" <?php echo $checked ?>>
                            </td>
                            <td><?php echo $row->Field; ?></td>
                            <td>
                                <?php
                                $fk = FALSE;
                                foreach ($dbschema as $dba) {
                                    if ($t->name == $dba->table) {
                                        $Ftableschema = $this->getTableSchema($dba->reftable);
                                        $strField = $Ftableschema[0]->Field;
                                        foreach ($Ftableschema as $f) {
                                            if ($f->Key != 'PRI') {
                                                $strField = $f->Field;
                                                break;
                                            }
                                        }
                                        if ($row->Field == $dba->fk) {
                                            echo '<select class="form-control" name="colunasVerTitleRef_' . $t->name . '_' . $row->Field . '">';
                                            foreach ($this->getTableSchema($dba->reftable) as $tbl) {
                                                $selected = $strField == $tbl->Field ? 'selected' : '';
                                                echo '<option value="' . $tbl->Field . '" ' . $selected . '>' . $dba->reftable . '.' . $tbl->Field . '</option>';
                                            }
                                            echo "</select>";
                                            $fk = true;
                                            break;
                                        }
                                    }
                                }
                                if (!$fk) {
                                    echo '<input type="hidden" '
                                    . 'name="colunasVerTitleRef_' . $t->name . '_' . $row->Field . '" '
                                    . 'value="' . ucfirst($t->name) . '.' . $row->Field . '">';
                                    echo $row->Field;
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                        <tr class="">
                        <th colspan="4">Incluir nesta página:</th>
                    </tr>
                    <?php
                    // under construction
                    $used = array();
                    foreach ($dbschema as $v) {
                        if ($v->reftable == $table) {
                            $mname = $this->getPlural(ucfirst(($v->table)));
                            $cused = '';
                            while (array_search($mname . $cused, $used)) {
                                $cused = (int) $cused + 1;
                            }
                            $used[] = $mname . $cused;
                            echo '<tr>';
                            echo '<td>';
                            echo '<input name="colunasVerLista_' . $t->name . '[]"
                                   value="' . ($v->table) . '__' . $mname . '__'.$v->fk.'__'.$v->refpk.'" type="checkbox" checked>';

                            echo '</td>';
                            echo '<td colspan="2">';
                            echo 'Lista de ' . $this->getPlural($v->table);
                            echo '</td>';
                            echo '<td colspan="1">';
                            echo ' <small>$' . ucfirst($table) . '->' . $mname . '()</small>';
                            echo '</td>';
                            echo '</tr>';                                                      

                            # hasNN
                            $NNschema = $this->getDbSchema();
                            foreach ($NNschema as $schema) {
                                if ($schema->table == $v->table) {
                                    # auto relacionamento??
                                    # se for, TO DO
                                    if ($table == $schema->reftable) {
                                        continue;
                                    }

                                    $mname = ucfirst($schema->table) . $this->getPlural(ucfirst(($schema->reftable)));
                                    $cused = '';
                                    while (array_search($mname . $cused, $used)) {
                                        $cused = (int) $cused + 1;
                                    }
                                    $used[] = $mname . $cused;

                                    echo '<tr>';
                                    echo '<td>';
                                    echo '<input name="colunasVerLista_' . $t->name . '[]"
                                   value="' . ($schema->reftable) . '__' . $mname . '" type="checkbox" checked/>';

                                    echo '</td>';
                                    echo '<td colspan="2">';
                                    echo 'Lista de ' . $this->getPlural($schema->reftable) . ' (NxN)';
                                    echo '</td>';
                                    echo '<td colspan="1">';
                                    echo ' <small>$' . ucfirst($table) . '->' . $mname . '()</small>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            }
                        }
                    }
                    ?>                   
                </table>

            </div>

        </div>
    </div>
</div>