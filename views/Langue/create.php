<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Langue */

$this->title = Yii::t('app', 'Create Langue');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Langues'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="langue-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
