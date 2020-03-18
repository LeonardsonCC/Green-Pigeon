<div class="panel panel-info" id="ConfigVisaoListar_<?php echo $t->name; ?>">
    <div class="panel-heading" data-toggle="collapse" data-parent="#configs-acordion<?php echo $id; ?>"
         href="#collapseVisaoListar_<?php echo $t->name; ?>">
        <h4 class="panel-title" style="cursor: pointer">
            <span class="glyphicon glyphicon-cog"></span>
            Configurar Visão <strong>listar</strong>
        </h4>
    </div>
    <div id="collapseVisaoListar_<?php echo $t->name; ?>" class="panel-collapse collapse">
        <div class="panel-body">

            <div class="col-md-12">
                <h4>Colunas que deverão aparecer na listagem</h4>

                <table class="table table-striped">
                    <tr>
                        <th>Exibir?</th>
                        <th>Atributo</th>
                        <th>Coluna vinculada</th>
                    </tr>

                    <?php
                    $tbn = $t->name;
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
                                <input name="colunasAll_<?php echo $t->name; ?>[]"
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
                                            echo '<select class="form-control" name="colunasTitleRef_' . $t->name . '_' . $row->Field . '">';
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
                                echo!$fk ? $row->Field : '';
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>

                <hr/>
                <h4>Formulário de pesquisa</h4>
                <div class="form-group">
                    <label>Pesquisar por:</label>
                    <select name="pesquisa_<?php echo $t->name; ?>" class="form-control">
                        <?php
                        $selected = false;
                        foreach ($tableSchema as $row):
                            if (!$selected && $row->Key != 'PRI') {
                                echo '<option value=' . $row->Field . ' selected>' . $row->Field . '</option>';
                                $selected = true;
                            } else {
                                echo '<option value=' . $row->Field . '>' . $row->Field . '</option>';
                            }
                        endforeach;
                        ?>
                    </select>
                </div>
            </div>

        </div>
    </div>
</div>