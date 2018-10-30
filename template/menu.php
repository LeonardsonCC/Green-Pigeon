<li>
    <?php echo $this->Html->getLink('Início', 'Index', 'index'); ?>
</li>
<li>
    <?php echo $this->Html->getLink('Eventos', 'Evento', 'lista'); ?>
</li>
<li>
    <?php echo $this->Html->getLink('Dicas', 'Dica', 'dicas'); ?>
</li>
<li>
    <?php echo $this->Html->getLink('Mapa', 'Ponto_coleta', 'mapa'); ?>
</li>


    <?php
    $usuario = Session::get('user');
    if(!$usuario){
        echo '<li>'.$this->Html->getLink('Entrar', 'login', '').'</li>';
    } 
    else{
        echo '
            <ul id="dropdown-menu" class="dropdown-content">
            <li><a href="'.$this->Html->getUrl('Usuario', 'ver', array($usuario->id)).'">Minha página</a></li>
            <li><a href="#!">Meus pontos</a></li>
            <li class="divider"></li>
            <li>'.$this->Html->getLink('Sair', 'Login', 'logout').'</li>
          </ul>
          <li>';
        echo '<li style="min-width: 150px;"><a class="dropdown-trigger" href="#!" data-target="dropdown-menu">'.$usuario->nome.'<i class="material-icons right">arrow_drop_down</i></a></li>';
    }
    ?>