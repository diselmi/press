<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\widgets\FileInput;
use kartik\widgets\DatePicker;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Media */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="media-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'nom')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'tv')->checkbox() ?>
    <?= $form->field($model, 'radio')->checkbox() ?>
    <?= $form->field($model, 'j_papier')->checkbox() ?>
    <?= $form->field($model, 'j_electronique')->checkbox() ?>

    <?= $form->field($model, 'adresse')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'mail')->input('email') ?>

    <?= $form->field($model, 'numtel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fichier_logo')->widget(FileInput::classname(), [
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

    <?= $form->field($model, 'date_creation')->widget(DateControl::classname(), [
                'type'      => DateControl::FORMAT_DATE ,
                'language'  => 'fr',
                'convertFormat' => true,
                'widgetOptions' => [
                    'type'  => DatePicker::TYPE_COMPONENT_APPEND,
                ],
                'pluginOptions' => [
                    'todayHighlight' => true,
                ]

            ]); ?>
    
    <?php
        $cUser = Yii::$app->user->identity;
        if ($cUser->role0->nom == "client" || $cUser->role0->type == "client" ) {
            $radioItems = ["0"=> ucfirst(Yii::t("app", "no")), "1"=> ucfirst(Yii::t("app", "yes")) ];
            echo $form->field($model, 'pr_value')->textInput(['maxlength' => true]);
        }
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
