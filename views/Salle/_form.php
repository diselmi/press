<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Salle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="salle-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'nom')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
        'options'=>[
            'multiple'=>false,
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'initialPreviewAsData'=>true,
            'overwriteInitial'=>true,
            'showPreview' => true,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false,
        ]
    ]); ?>

    <?= $form->field($model, 'capacite')->textInput() ?>

    <?= $form->field($model, 'connectique')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gouvernerat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'adresse')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vis_a_vis')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'numtel')->textInput(['maxlength' => true]) ?>
    
    <?php $radioItems = ["0"=> ucfirst(Yii::t("app", "no")), "1"=> ucfirst(Yii::t("app", "yes")) ]; ?>
    <?= $form->field($model, 'est_premium')->radioList($radioItems) ?>
    
    <?= "<label>Gallery photos</label>" ?>
    <?= FileInput::widget([
        'name' => 'photos[]',
        'options'=>[
            'multiple'=>true,
            'accept' => 'image/*'
        ],
        'pluginOptions' => [
            'previewFileType' => 'any',
            'initialPreviewAsData'=>true,
            'overwriteInitial'=>true,
            'showPreview' => true,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false,
        ]
    ]); ?>
    
    <br />
    <?= "<label>Documents</label>" ?>
    <?= FileInput::widget([
        'name' => 'documents[]',
        'options'=>[
            'multiple'=>true,
            'accept' => 'application/pdf',
        ],
        'pluginOptions' => [
            'previewFileType' => 'any',
            'initialPreviewAsData'=>true,
            'overwriteInitial'=>true,
            'showPreview' => true,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false,
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
