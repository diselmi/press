<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PublicationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="publication-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'lang') ?>

    <?= $form->field($model, 'titre') ?>

    <?= $form->field($model, 'type_contenu') ?>

    <?= $form->field($model, 'contenu_html') ?>

    <?php // echo $form->field($model, 'contenu_doc') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'event') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
