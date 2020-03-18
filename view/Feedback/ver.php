<?php
# Visão view/Feedback/ver.php 
/* @var $this FeedbackController */
/* @var $Feedback Feedback */
?>
<div class="ver feedback card">
<div class="panel-body">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h1><?php echo $Feedback->texto;?></h1>
        </div>
        <div class="panel-body">
            <div class="atributo email col-sm-6">
                <div class="name col-md-3"><strong>Email</strong>: </div>
                <div class="value col-md-9"><?php echo $Feedback->email;?></div>
            </div>
            <div class="atributo receber_email col-sm-12">
                <div class="name"><strong>Receber_email</strong>: </div>
                <div class="value"><?php echo $Feedback->receber_email;?></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <br>
    

    
</div>
</div>
<!-- LazyPHP.com.br -->