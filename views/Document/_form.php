<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\widgets\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <?= FileInput::widget([
        'name' => 'fichiers[]',
        'options'=>[
            'multiple'=>true
        ],
        'pluginOptions' => [
            'uploadUrl' => Url::to(['/document/upload-files']),
            'initialPreviewAsData'=>true,
            'overwriteInitial'=>true,
            'showPreview' => true,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => true,
            'initialPreview'=> [
               //Url::to(["/images/image_upload_holder.png"]),
            ],
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Upload'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
