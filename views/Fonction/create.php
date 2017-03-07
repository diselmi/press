<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fonction */

$this->title = Yii::t('app', 'Create Fonction');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fonctions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fonction-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
