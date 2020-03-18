<div class="panel panel-primary">
    <div class="panel-heading" data-toggle="collapse" data-parent="#configs-acordion<?php echo $id; ?>"
         href="#collapseCadastro_<?php echo $t->name; ?>">
        <h4 class="panel-title" style="cursor: pointer">
            <span class="glyphicon glyphicon-cog"></span>
            Configurações Gerais de Semântica
        </h4>
    </div>
    <div id="collapseCadastro_<?php echo $t->name; ?>" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="col-md-12">
                <div class="alert alert-warning">
                    As configurações deste bloco intereferem nas três camadas MVC do sistema
                </div>
                <h4>Titulo dos campos do formuário</h4>
                <hr/>

                <table class="table table-striped">
                    <tr>
                        <th></th>
                        <th>Título do Campo (label)</th>
                        <th>Finalidade</th>
                    </tr>

                    <?php
                    $tableschema = $this->getTableSchema($t->name);
                    $priField = NULL;
                    foreach ($tableschema as $atributo):
                        ?>
                        <tr>

                            <?php
                            foreach ($dbschema as $dbs):
                                if ($dbs->table == $t->name && $dbs->fk == $atributo->Field):
                                    ?>
                            <td class="text-danger">
                                        <strong>[FK] <?php echo $atributo->Field ?></strong>
                                        <br>
                                        <small> <?php echo $atributo->Type ?></small>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"
                                               name="form_<?php echo $t->name; ?>_<?php echo $atributo->Field; ?>"
                                               value="<?php echo ucfirst($dbs->reftable); ?>"/>
                                    </td>  
                                    <td>
                                        <select class="form-control" 
                                                name="tipo_<?php echo $t->name; ?>_<?php echo $atributo->Field; ?>">
                                            <option value="selectFK">Select option</option>
                                            <option value="radioFK">Input radio</option>
                                        </select>
                                    </td>
                                    <?php
                                    continue 2;
                                endif;
                            endforeach;
                            ?>
                            <td>
                                <strong><?php echo $atributo->Field ?></strong>
                                <br>
                                <small> <?php echo $atributo->Type ?></small>
                            </td>
                            <td>
                                <input type="text" class="form-control"
                                       name="form_<?php echo $t->name; ?>_<?php echo $atributo->Field; ?>"
                                       value="<?php echo ucfirst($atributo->Field); ?>"/>
                            </td>  
                            <td>    
                                <select class="form-control" 
                                        name="tipo_<?php echo $t->name; ?>_<?php echo $atributo->Field; ?>">
                                    <?php
                                    $select['text'] = NULL;
                                    $select['number'] = NULL;
                                    $select['now'] = NULL;
                                    $select['date'] = NULL;
                                    $select['time'] = NULL;
                                    $select['now'] = NULL;
                                    $select['password'] = NULL;
                                    $select['checkbox'] = NULL;
                                    $select['decimal'] = NULL;
                                    $select['textarea_html'] = NULL;
                                    if (strstr($atributo->Type, 'char')) {
                                        if ($atributo->Type == 'char(32)') {
                                            $select['password'] = 'selected';
                                        } else {
                                            $select['text'] = 'selected';
                                        }
                                    } elseif (strstr($atributo->Type, 'int(1)')) {
                                        $select['checkbox'] = 'selected';
                                    } elseif (strstr($atributo->Type, 'int')) {
                                        $select['number'] = 'selected';
                                    } elseif (strstr($atributo->Type, 'decimal')) {
                                        $select['decimal'] = 'selected';
                                    } elseif ($atributo->Type == 'date') {
                                        $select['date'] = 'selected';
                                    } elseif ($atributo->Type == 'datetime') {
                                        $select['now'] = 'selected';
                                    } elseif ($atributo->Type == 'time') {
                                        $select['time'] = 'selected';
                                    } elseif (strstr($atributo->Type, 'text')) {
                                        $select['textarea_html'] = 'selected';
                                    }
                                    ?>
                                    <option value="text" <?php echo $select['text']; ?>>linha de texto</option>
                                    <option value="number" <?php echo $select['number']; ?>>número</option>
                                    <option value="now" <?php echo $select['now']; ?>>agora (data e hora atual)</option>
                                    <option value="date" <?php echo $select['date']; ?>>data</option>
                                    <option value="datetime">data e hora</option>
                                    <option value="time" <?php echo $select['time']; ?>>horas</option>
                                    <option value="password" <?php echo $select['password']; ?>>
                                        senha para md5 char(32)
                                    </option>
                                    <option value="checkbox" <?php echo $select['checkbox']; ?>>Checkbox  (boolean)</option>
                                    <option value="decimal">Moeda ou decimais</option>
                                    <option value="email">E-mail</option>
                                    <option value="textarea">Texto longo</option>
                                    <option value="textarea_html" <?php echo $select['textarea_html']; ?>>
                                        Texto longo com HTML
                                    </option>
                                    <option value="image">imagem com upload (não blob)</option>
                                    <option value="file">arquivo com upload (não blob)</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>