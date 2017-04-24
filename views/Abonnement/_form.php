<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\Module;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Abonnement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="abonnement-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'etat')->dropDownList($liste_etats) ?>
    
    <?= $form->field($model, 'date_fin')->widget(DatePicker::classname(), [
        'type'      => DatePicker::TYPE_COMPONENT_APPEND,
        'language' => 'fr',
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd'
        ]

    ]) ?>

    <?= $form->field($model, 'vis_a_vis')->dropDownList($liste_admins) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
