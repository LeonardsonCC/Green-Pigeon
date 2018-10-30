<li>
    <?php echo $this->Html->getLink('<i class="material-icons">home</i>', 'Index', 'index'); ?>
</li>
<li>
    <?php echo $this->Html->getLink('<i class="material-icons">info</i>', 'Dica', 'dicas'); ?>
</li>
<li>
    <?php echo $this->Html->getLink('<i class="material-icons">place</i>', 'Ponto_coleta', 'mapa'); ?>
</li>
<li>
    <?php
    if(!Session::get('user')){
        echo $this->Html->getLink('<i class="material-icons">person</i>', 'login', '');
    } 
    else{
        echo $this->Html->getLink('<i class="material-icons">exit_to_app</i>', 'Login', 'logout');
    }
    ?>
</li>