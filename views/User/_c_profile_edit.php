<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\ColorInput;
use kartik\widgets\DatePicker;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="user-update">
<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            
    <?= $form->field($model, 'nom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prenom')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'photo')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*', 'multiple' => true],
        'pluginOptions' => [
            'showPreview' => false,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false
        ]
    ]); ?>

    <?= $form->field($model, 'lang')->dropDownList($lang_array) ?>
    
    <?= $form->field($model, 'fonction')->dropDownList($fonction_array) ?>

    <?= $form->field($model, 'adresse')->textarea(['maxlength' => true]) ?>
    
    <?php    if ( !$model->isNewRecord) { ?>

        <?= $form->field($model, 'login')->textInput() ?>
        <?= $form->field($model, 'pass')->textInput() ?>

    <?php } ?>
    
    <?= $form->field($model, 'couleur_interface')->widget(ColorInput::classname(), [
        'options' => ['placeholder' => 'Select color ...'],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
