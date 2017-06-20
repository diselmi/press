<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ContactSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contact-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'nom') ?>

    <?= $form->field($model, 'prenom') ?>

    <?= $form->field($model, 'numtel') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'adresse') ?>

    <?php // echo $form->field($model, 'rs') ?>

    <?php // echo $form->field($model, 'objet') ?>

    <?php // echo $form->field($model, 'contenu') ?>

    <?php // echo $form->field($model, 'apropos') ?>

    <?php // echo $form->field($model, 'entendu_par') ?>

    <?php // echo $form->field($model, 'etre_notifie') ?>

    <?php // echo $form->field($model, 'etre_contacte') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
