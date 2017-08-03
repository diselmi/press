<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\Role */

$this->title = $model->id.": ".$model->nom;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if ($model->type != "superadmin") {
            echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
            echo Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]);
        }
        ?>
            
        
    </p>

    <?php if( $model->type == "admin" ) { ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nom',
            'type',
            'user_gerer:boolean',
            'prestataire_gerer:boolean',
            'site_gerer:boolean',
            'contact_gerer:boolean',
        ],
    ]) ?>
    <?php }else { ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nom',
            'type',
            'user_gerer:boolean',
            'prestataire_gerer:boolean',
            'evenement_gerer:boolean',
        ],
    ]) ?>
    <?php } ?>
    
    
    <h2> <?= ucfirst(Yii::t('app', 'utilisataurs')) ?>: </h2>
    
    <?php
        $usersProvider = new ArrayDataProvider([
            'allModels' => $model->users,
            'sort' => [
                'attributes' => ['id', 'nom', 'mail'],
            ],
            'pagination' => [
                'pageSize' => 2,
            ],
        ]);
    ?>
    
    <?= GridView::widget([
        'dataProvider' => $usersProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nom',
            'mail',

        ],
    ]); ?>


</div>
