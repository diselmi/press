<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Nouveaute */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nouveaute-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'titre')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*', 'multiple' => false],
        'pluginOptions' => [
            'showPreview' => false,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false
        ]
    ]); ?>

    <?= $form->field($model, 'libelle_court')->textarea() ?>

    <?= $form->field($model, 'libelle_long')->textarea(['rows' => 5]) ?>

    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
