<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Temoignage */

$this->title = Yii::t('app', 'Create Temoignage');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Temoignages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="temoignage-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
