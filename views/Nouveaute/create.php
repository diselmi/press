<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Nouveaute */

$this->title = Yii::t('app', 'Create Nouveaute');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Nouveautes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nouveaute-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
