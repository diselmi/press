<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">
        <h1><a class="btn btn-default" href="/user/index">Users &raquo;</a></h1>
        <h1><a class="btn btn-default" href="/role/index">Roles &raquo;</a></h1>
        <h1><a class="btn btn-default" href="/abonnement/index">Abonnements &raquo;</a></h1>
        <h1><a class="btn btn-default" href="/privilege/index">Privileges &raquo;</a></h1>
        <h1><a class="btn btn-default" href="/nouveaute/index">Nouveautes &raquo;</a></h1>
        <h1><a class="btn btn-default" href="/document/index">Documents &raquo;</a></h1>
        
        <h1> Nouveautes </h1>
        <?= dosamigos\gallery\Carousel::widget([
            'items' => $liste_nouv,
            'json' => true,
            'clientEvents' => [
                'onslide' => 'function(index, slide) {
                    console.log(slide);
                }'
        ]]); ?>
        

        
        

    </div>
</div>
