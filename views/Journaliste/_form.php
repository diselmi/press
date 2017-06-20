<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\widgets\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Journaliste */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="journaliste-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'nom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mail')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'numtel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fichier_photo')->widget(FileInput::classname(), [
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
    
    <?= $form->field($model, 'theme')->dropDownList($themes) ?>
    
    <h3> <?= ucfirst(Yii::t('app', 'medias associes')) ?> </h3>
    
    
    <?= $this->render('_media_subform', []) ?>
    
    
    
    <div class="form-group">
        <br>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    
    
    
    

    <?php ActiveForm::end(); ?>

</div>


<?php $this->registerJsFile(
    Url::to("/js/media_add.js"),
    [
        'position' => 3, // Body End
        'depends' => 'yii\bootstrap\BootstrapAsset'
    ]
);?>
