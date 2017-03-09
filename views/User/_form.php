<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\ColorInput;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'mail')->input('email') ?>
            
    <?= $form->field($model, 'nom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prenom')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'photo')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'showPreview' => false,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false
        ]
    ]); ?>

    <?= $form->field($model, 'lang')->dropDownList($lang_array) ?>
    
    <?= $form->field($model, 'role_type')->dropDownList($role_types_array, ['onchange' => 'userRoleChanged(this);']) ?>

    
    <div id="user_form_client">
        
        <?= $form->field($model, 'role')->dropDownList($role_array) ?>
    
        <?= $form->field($model, 'fonction')->dropDownList($fonction_array) ?>

        <?= Html::textInput('new_fonction', '', ['class'=> 'form-control', 'id'=>'newFonctionInput', 'placeholder'=>'Entrez une nouvelle fonction']) ?>

        <br/>
        <?= $form->field($model, 'logo')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/*'],
            'pluginOptions' => [
                'showPreview' => false,
                'showCaption' => true,
                'showRemove' => true,
                'showUpload' => false
            ]
        ]); ?>

        <?= $form->field($model, 'adresse')->textarea(['maxlength' => true]) ?>

        <?= $form->field($model, 'domaines')->textarea(['maxlength' => true]) ?>

        <?= $form->field($model, 'couleur_interface')->widget(ColorInput::classname(), [
            'options' => ['placeholder' => 'Select color ...'],
        ]); ?>
        
    </div>
    
    <div id="user_form_role_admin">
        <?= $form->field($model, 'role')->dropDownList($role_admin_array) ?>
    </div>
    
    <?php    if ( !$model->isNewRecord) { ?>

        <?= $form->field($model, 'login')->textInput() ?>
        <?= $form->field($model, 'pass')->textInput() ?>

    <?php } ?>
    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
