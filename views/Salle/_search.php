<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SalleSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="salle-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nom') ?>

    <?= $form->field($model, 'capacite') ?>

    <?= $form->field($model, 'connectique') ?>

    <?= $form->field($model, 'gouvernerat') ?>

    <?php // echo $form->field($model, 'adresse') ?>

    <?php // echo $form->field($model, 'vis_a_vis') ?>

    <?php // echo $form->field($model, 'cree_par') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
