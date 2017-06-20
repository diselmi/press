<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FournisseurSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fournisseur-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nom') ?>

    <?= $form->field($model, 'adresse') ?>

    <?= $form->field($model, 'numtel') ?>

    <?= $form->field($model, 'activite') ?>

    <?php // echo $form->field($model, 'estPremium') ?>

    <?php // echo $form->field($model, 'gallery_photos') ?>

    <?php // echo $form->field($model, 'gallery_pdf') ?>

    <?php // echo $form->field($model, 'siteweb') ?>

    <?php // echo $form->field($model, 'facebook') ?>

    <?php // echo $form->field($model, 'twitter') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
