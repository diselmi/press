<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Inflector;

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
            <?= Html::a(Yii::t('app', 'Switch'), ['switch', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Update'), ['c-update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['c-delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
            
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
                'value' => function($model) {
                            if ($model->role0->nom != "client") {
                                return $model->role0->nom ;
                            }else {
                                return "-";
                            }
                        }
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
    
    <hr>
    <h3> <?= ucfirst(Yii::t('app', 'superieur')) ?> </h3>
    <hr>
    
    <h3> <?= ucfirst(Yii::t('app', 'vis a vis')) ?> </h3>
    <?= DetailView::widget([
        'model' => $model->superieur0->abonnement0->vis_a_vis0,
        'attributes' => [
            [
                'label' => Yii::t('app', 'Nom'),
                'value' => function($model) {
                    return $model->nom." ".$model->prenom;
                }  
            ],
            [
                'label' => Yii::t('app', 'Login'),
                'value' => function($model) {
                    return $model->login;
                }  
            ],
            [
                'label' => Yii::t('app', 'Mail'),
                'value' => function($model) {
                    return $model->mail;
                }  
            ],
        ],
    ]) ?>
    
    <h3> <?= ucfirst(Yii::t('app', 'abonnement')) ?> </h3>
    <?= DetailView::widget([
        'model' => $model->superieur0->abonnement0,
        'attributes' => [
            [
                'label' => Yii::t('app', 'Etat'),
                'value' => function($model, $widget){
                    /*if ($model->etat) {
                        return Yii::t('app', 'Valide');
                    }
                    return Yii::t('app', 'non valide');*/
                    return Yii::t('app', $model->etat);
                }
            ],
            'date_debut',
            'date_fin'
        ],
    ]) ?>

</div>
