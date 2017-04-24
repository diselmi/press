<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Abonnement */

$this->title = Yii::t('app', 'Create Abonnement');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Abonnements'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="abonnement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
