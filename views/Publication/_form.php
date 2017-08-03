<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use kartik\widgets\FileInput;
use yii\helpers\Url;

use dosamigos\ckeditor\CKEditor;


/* @var $this yii\web\View */
/* @var $model app\models\Publication */
/* @var $form yii\widgets\ActiveForm */
?>

<?= Html::csrfMetaTags() ?>

<div class="publication-form">
    

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    
    <?php // echo $form->errorSummary($model) ?>
    
    <?= $form->field($model, 'champ_image')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*', 'multiple' => false],
        'pluginOptions' => [
            'showPreview' => false,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false
        ]
    ]); ?>

    <?= $form->field($model, 'lang')->dropDownList($lang_array) ?>

    <?= $form->field($model, 'titre')->textInput(['maxlength' => true]) ?>
    
    <?php //echo $form->field($model, 'event')->textInput() ?>

    <?php $types = ["0"=>Yii::t("app", "Contenu Html"), "1"=>Yii::t("app", "Document")]; ?>
    <?= $form->field($model, 'type_contenu')->dropDownList($types) ?>
    
    
    <?php // echo $form->field($model, 'contenu_doc')->textInput(); ?>
    
    <div id="publication_contenu_doc">
    <?= $form->field($model, 'champ_doc')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/* | .pdf', 'multiple' => false],
        'pluginOptions' => [
            'showPreview' => false,
            'showCaption' => true,
            'showRemove' => true,
            'showUpload' => false
        ]
    ]); ?>
    </div>
    
    <?php //echo $form->field($model, 'contenu_html')->textarea(['rows' => 6]) ?>
    <div id="publication_contenu_html">
    <?= $form->field($model, 'contenu_html')->widget(CKEditor::className(), [
        'options' => [
            'rows' => 20,
        ],
        'preset' => 'full',
        'clientOptions' => [
            'extraPlugins' => 'colordialog,colorbutton,font,justify,uploadimage,filebrowser',
            'imageUploadUrl' => Url::to('/publication/upload-editor-image'),
        ]
        
    ]) ?>
    </div>

    
    

    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$this->registerJsFile(
    Url::to("/js/publication_changer_type.js"),
    [
        'position' => 3, // Body End
        'depends' => 'yii\bootstrap\BootstrapAsset'
    ]
);

$this->registerJs("CKEDITOR.plugins.addExternal('colordialog', '/js/ckeditor/colordialog/plugin.js', '');", 3 );
$this->registerJs("CKEDITOR.plugins.addExternal('colorbutton', '/js/ckeditor/colorbutton/plugin.js', '');", 3 );
$this->registerJs("CKEDITOR.plugins.addExternal('justify', '/js/ckeditor/justify/plugin.js', '');", 3 );
$this->registerJs("CKEDITOR.plugins.addExternal('uploadimage', '/js/ckeditor/uploadimage/plugin.js', '');", 3 );
$this->registerJs("CKEDITOR.plugins.addExternal('font', '/js/ckeditor/font/plugin.js', '');", 3 );
$this->registerJs("CKEDITOR.config.colorButton_enableMore = true;", 3);

?>
