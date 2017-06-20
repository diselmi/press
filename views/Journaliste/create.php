<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Journaliste */

$this->title = Yii::t('app', 'Create Journaliste');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Journalistes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="journaliste-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'themes'=> $themes
    ]) ?>

</div>
