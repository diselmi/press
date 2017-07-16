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

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php    if ( $model->isNewRecord) { ?>
        <?= $form->field($model, 'mail')->input('email') ?>
    <?php } ?>
            
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
    
    <?= $form->field($model, 'role')->dropDownList($role_client_array) ?>
    
    <?php    if ( !$model->isNewRecord) { ?>

        <?php //echo $form->field($model, 'login')->textInput() ?>
        <?php //echo $form->field($model, 'pass')->textInput() ?>

    <?php } ?>
    
    <?= $form->field($model, 'fonction')->dropDownList($fonction_array) ?>
    
    <?= Html::textInput('new_fonction', '', ['class'=> 'form-control', 'id'=>'newFonctionInput', 'placeholder'=>'Entrez une nouvelle fonction']) ?>
    <br />

    <?= $form->field($model, 'adresse')->textarea(['maxlength' => true]) ?>
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
