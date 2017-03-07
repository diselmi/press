<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Admin */

$this->title = $model->id .": ". $model->nom ." ". $model->prenom;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admins'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;
?>
<div class="admin-view">

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
            'photo',
            'nom',
            'prenom',
            'mail',
            [
                'label' => 'role',
                'value' => $model->role0->nom,
            ],
            'login',
            'pass',
            [
                'label' => 'lang',
                'value' => $model->lang0->nom,
            ],
        ],
    ]) ?>

</div>
