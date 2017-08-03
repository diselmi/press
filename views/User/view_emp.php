<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Inflector;

use yii\grid\GridView;
use yii\widgets\Pjax;

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
            <?php if (Yii::$app->user->identity->role0 == "client" && $model->superieur = Yii::$app->user->id) { ?>
                
            
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
            
            <?php } ?>
            
        </p> 
    </div>
    
    <h3> <?= ucfirst(Yii::t('app', 'employe')) ?> </h3>
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'nom',
            'prenom',
            'mail',
            'login',
            //'pass',
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
            //'domaines',
            
            //'couleur_interface',
        ],
    ]) ?>

    
    <h3> <?= ucfirst(Yii::t("app", "permissions")) ?> </h3>
    <?= DetailView::widget([
        'model' => $model->role0,
        'attributes' => [
            'user_gerer:boolean',
            'prestataire_gerer:boolean',
            'evenement_gerer:boolean',
        ],
    ]) ?>


</div>
