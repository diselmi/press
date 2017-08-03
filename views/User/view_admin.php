<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Inflector;

/* @var $this yii\web\View */
/* @var $model app\models\Role */

$this->title = $model->nom ." ". $model->prenom;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-view">
    
    <div class="profile_picture_admin">
        <?= Html::img($model->photo) ?>
    </div>
    
    <div class="profile_title_admin">
        <h1><?= ucfirst(Html::encode($this->title)) ?></h1>
        <p>
            <?php $disabled = ""; if ($model->role0->nom == "superadmin"){ $disabled = "disabled";} ?>
            
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary '.$disabled]) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger '.$disabled,
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
            [
                'label' => Yii::t('app', 'role'),
                'value' => $model->role0->type.": ".$model->role0->nom,
            ],
        ],
    ]) ?>
    
    <h3> <?= ucfirst(Yii::t("app", "permissions")) ?> </h3>
    <?= DetailView::widget([
        'model' => $model->role0,
        'attributes' => [
            'user_gerer:boolean',
            'prestataire_gerer:boolean',
            'site_gerer:boolean',
            'contact_gerer:boolean',
        ],
    ]) ?>
    

</div>
