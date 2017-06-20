<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Fournisseur */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fournisseur-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'nom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'adresse')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'numtel')->textInput(['maxlength' => true]) ?>
    
    <?php $liste_activites = ["activite1"=>"activite1", "activite2", "activite3"]; $liste_activites = array_combine($liste_activites, $liste_activites) ?>
    <?= $form->field($model, 'activite')->dropDownList($liste_activites) ?>
    
    <?= $form->field($model, 'logo')->widget(FileInput::classname(), [
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

    <?= $form->field($model, 'siteweb')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'facebook')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'twitter')->textInput(['maxlength' => true]) ?>
    
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
    
    <br />
    <?= $form->field($model, 'estPremium')->checkbox(['label' => ucFirst(Yii::t("app", 'est un membre premium'))]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
