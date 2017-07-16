<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Abonnement */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Abonnements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="abonnement-view">

    <h1><?= Html::encode($this->title) ?></h1>

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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => ucfirst(Yii::t("app", "client")),
                'value' => ($model->client0->id).": ".$model->client0->nom." ".$model->client0->prenom,
            ],
            'date_debut:date',
            'date_fin:date',
            'etat',
            [
                'label' => ucfirst(Yii::t("app", "vis a vis")),
                'value' => ($model->vis_a_vis0->id).": ".$model->vis_a_vis0->nom." ".$model->vis_a_vis0->prenom,
            ],
            
        ],
    ]) ?>
    <h3> <?= ucfirst(Yii::t("app", "acces premium")) ?> </h3>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'acces_salles:boolean',
            'acces_journalistes:boolean'
        ],
    ]) ?>

</div>
