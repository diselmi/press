<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Abonnement */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Abonnement',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Abonnements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="abonnement-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model'         => $model,
        'liste_admins'   => $liste_admins,
        'liste_etats'   => $liste_etats,
    ]) ?>

</div>
