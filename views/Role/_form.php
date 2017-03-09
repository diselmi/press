<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Role */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="role-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nom')->textInput(['maxlength' => true]) ?>

    <?php $type_array = array('superadmin' => Yii::t('app', 'superadmin'), 'admin' => Yii::t('app', 'admin'), 'client' => Yii::t('app', 'client')); ?>
    <?= $form->field($model, 'type')->dropDownList($type_array) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php echo $form->field($model, 'users')->checkboxList($users_array); ?>
    
    
    
    <?php ActiveForm::end(); ?>

</div>
