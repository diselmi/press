<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->nom ." ". $model->prenom;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
?>
<div class="user-view">
    
    <div class="profile_picture_admin">
        <?= Html::img($model->logo) ?>
    </div>
    <div class="profile_picture_admin">
        <?= Html::img($model->photo) ?>
    </div>
    
    <div class="profile_title_admin">
        <h1><?= ucfirst(Html::encode($this->title)) ?></h1>
        <p>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p> 
    </div>
    

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nom',
            'prenom',
            'mail',
            'login',
            'pass',
            [
                'label' => 'lang',
                'value' => $model->lang0->nom,
            ],
            [
                'label' => 'role',
                'value' => $model->role0->nom,
            ],
            [
                'label' => 'fonction',
                'value' => $model->fonction0->nom,
            ],
            'adresse',
            'domaines',
            
            'couleur_interface',
        ],
    ]) ?>

</div>
