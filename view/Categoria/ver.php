<?php
# Visão view/Categoria/ver.php 
/* @var $this CategoriaController */
/* @var $Categoria Categoria */
?>
<div class="ver categoria panel panel-default">
<div class="panel-body">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h1><?php echo $Categoria->nome;?></h1>
        </div>
        <div class="panel-body">
            <div class="atributo link col-sm-6">
                <div class="name col-md-3"><strong>Link</strong>: </div>
                <div class="value col-md-9"><?php echo $Categoria->link;?></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <br>
    

    
    <div class="text-right">
        <?php
        echo $this->Html->getModalLink(
            '<i class="fa fa-plus-circle"></i> Cadastrar dica', 
            'Dica', 'cadastrar', 
            array('categoria_id' => $Categoria->id, 'url_origem' => Cript::cript($this->getCurrentURL())),
            array('class' => 'btn btn-default')
            );
        ?>
    </div><?php
/* @var $Dicas Dica[] */
?>
    <div class="lista Dica panel panel-default">
        <div class="panel-heading"><h2 class="panel-title">Dicas</h2></div>
        <div class="panel-body">
        <!-- tabela de resultados de Dicas -->
        <div class="clearfix">  
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Título</th>
                        <th>Usuário criador</th>
                    </tr>
                    </thead>
                    <?php
                    foreach ($Dicas as $d) {
                        echo '<tr>';
                        echo '<td>';
                        echo $this->Html->getLink($d->titulo, 'Dica', 'ver',
                            array($d->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '<td>';
                        echo $this->Html->getLink($d->getUsuario()->email, 'Usuario', 'ver',
                            array($d->getUsuario()->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>

                <!-- menu de paginação -->
                <div style="text-align:center"><?php echo $Dicas->getNav(); ?></div>
            </div> <!-- .table-reponsive -->
        </div>  <!-- .clearfix -->

    </div> <!-- .panel-body -->
    </div> <!-- .panel .Dica -->
    

    

    <div class="text-right">
        <?php
        echo $this->Html->getModalLink(
            '<i class="fa fa-plus-circle"></i> Cadastrar usuario', 
            'Usuario', 'cadastrar', 
            array('url_origem' => Cript::cript($this->getCurrentURL())),
            array('class' => 'btn btn-default')
            );
        ?>
    </div><?php
/* @var $DicaUsuarios Usuario[] */
?>
    <div class="lista Usuario panel panel-default">
        <div class="panel-heading"><h2 class="panel-title">Usuarios</h2></div>
        <div class="panel-body">
        <!-- tabela de resultados de Usuarios -->
        <div class="clearfix">  
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Email</th>
                        <th>Nome</th>
                        <th>Pontuação</th>
                    </tr>
                    </thead>
                    <?php
                    foreach ($DicaUsuarios as $u) {
                        echo '<tr>';
                        echo '<td>';
                        echo $this->Html->getLink($u->email, 'Usuario', 'ver',
                            array($u->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '<td>';
                        echo $this->Html->getLink($u->nome, 'Usuario', 'ver',
                            array($u->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '<td>';
                        echo $this->Html->getLink($u->pontuacao, 'Usuario', 'ver',
                            array($u->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>

                <!-- menu de paginação -->
                <div style="text-align:center"><?php echo $DicaUsuarios->getNav(); ?></div>
            </div> <!-- .table-reponsive -->
        </div>  <!-- .clearfix -->

    </div> <!-- .panel-body -->
    </div> <!-- .panel .Usuario -->
    

    

    <div class="text-right">
        <?php
        echo $this->Html->getModalLink(
            '<i class="fa fa-plus-circle"></i> Cadastrar ponto_coleta', 
            'Ponto_coleta', 'cadastrar', 
            array('categoria_id' => $Categoria->id, 'url_origem' => Cript::cript($this->getCurrentURL())),
            array('class' => 'btn btn-default')
            );
        ?>
    </div><?php
/* @var $Ponto_coletas Ponto_coleta[] */
?>
    <div class="lista Ponto_coleta panel panel-default">
        <div class="panel-heading"><h2 class="panel-title">Ponto_coletas</h2></div>
        <div class="panel-body">
        <!-- tabela de resultados de Ponto_coletas -->
        <div class="clearfix">  
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Aprovações</th>
                        <th>Desaprovações</th>
                        <th>Categoria</th>
                    </tr>
                    </thead>
                    <?php
                    foreach ($Ponto_coletas as $p) {
                        echo '<tr>';
                        echo '<td>';
                        echo $this->Html->getLink($p->nome, 'Ponto_coleta', 'ver',
                            array($p->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '<td>';
                        echo $this->Html->getLink($p->latitude, 'Ponto_coleta', 'ver',
                            array($p->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '<td>';
                        echo $this->Html->getLink($p->longitude, 'Ponto_coleta', 'ver',
                            array($p->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '<td>';
                        echo $this->Html->getLink($p->aprovacoes, 'Ponto_coleta', 'ver',
                            array($p->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '<td>';
                        echo $this->Html->getLink($p->desaprovacoes, 'Ponto_coleta', 'ver',
                            array($p->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '<td>';
                        echo $this->Html->getLink($p->getCategoria()->nome, 'Categoria', 'ver',
                            array($p->getCategoria()->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>

                <!-- menu de paginação -->
                <div style="text-align:center"><?php echo $Ponto_coletas->getNav(); ?></div>
            </div> <!-- .table-reponsive -->
        </div>  <!-- .clearfix -->

    </div> <!-- .panel-body -->
    </div> <!-- .panel .Ponto_coleta -->
    

    

    <div class="text-right">
        <?php
        echo $this->Html->getModalLink(
            '<i class="fa fa-plus-circle"></i> Cadastrar usuario', 
            'Usuario', 'cadastrar', 
            array('url_origem' => Cript::cript($this->getCurrentURL())),
            array('class' => 'btn btn-default')
            );
        ?>
    </div><?php
/* @var $Ponto_coletaUsuarios Usuario[] */
?>
    <div class="lista Usuario panel panel-default">
        <div class="panel-heading"><h2 class="panel-title">Usuarios</h2></div>
        <div class="panel-body">
        <!-- tabela de resultados de Usuarios -->
        <div class="clearfix">  
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Email</th>
                        <th>Nome</th>
                        <th>Pontuação</th>
                    </tr>
                    </thead>
                    <?php
                    foreach ($Ponto_coletaUsuarios as $u) {
                        echo '<tr>';
                        echo '<td>';
                        echo $this->Html->getLink($u->email, 'Usuario', 'ver',
                            array($u->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '<td>';
                        echo $this->Html->getLink($u->nome, 'Usuario', 'ver',
                            array($u->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '<td>';
                        echo $this->Html->getLink($u->pontuacao, 'Usuario', 'ver',
                            array($u->id), // variaveis via GET opcionais
                            array()); // atributos HTML opcionais
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>

                <!-- menu de paginação -->
                <div style="text-align:center"><?php echo $Ponto_coletaUsuarios->getNav(); ?></div>
            </div> <!-- .table-reponsive -->
        </div>  <!-- .clearfix -->

    </div> <!-- .panel-body -->
    </div> <!-- .panel .Usuario -->
    

    

</div>
</div>
<!-- LazyPHP.com.br -->