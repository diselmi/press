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
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    
    <?php $types_roles = array("admin"=> Yii::t("app",'admin'), "client"=> Yii::t("app",'client')) ?>
    
    <?= $form->field($model, 'type')->dropDownList($types_roles) ?>
    <?= $form->field($model, 'nom')->textInput(['maxlength' => true]) ?>
    
    <div>
        <h3>Permissions</h3>
    </div>
    
    <?= $form->field($model, 'user_gerer')->checkbox(['label'=>Yii::t('app', 'gerer les utilisateurs')]) ?>
    <?= $form->field($model, 'role_gerer')->checkbox(['label'=>Yii::t('app', 'gerer les roles')]) ?>
    
    <?php /*echo $form->field($model, 'users')->checkboxList($users_array, [
        'item'=>function ($index, $label, $name, $checked, $value){
            $affichage  = "<div class='checkbox'>";
            $affichage .= "<label>";
            $affichage .= "<input type='checkbox' name='$name' value='$value' ";
            $affichage .= $checked? 'checked': '';
            $affichage .= "> $label</label></div>";
            return $affichage ;
        }
        
    ]);*/ ?>
    
    
    
    <?php ActiveForm::end(); ?>

</div>
