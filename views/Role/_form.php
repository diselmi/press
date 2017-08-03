<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\grid\GridView;
use yii\widgets\Pjax;

use yii\helpers\Url;

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
    
    <div id="role_type_admin" style="display: none ;">
    <?= $form->field($model, 'user_gerer')->checkbox(['label'=>Yii::t('app', 'gerer les utilisateurs')]) ?>
    <?= $form->field($model, 'site_gerer')->checkbox(['label'=>Yii::t('app', 'gerer le site')]) ?>
    <?= $form->field($model, 'prestataire_gerer')->checkbox(['label'=>Yii::t('app', 'gerer les prestataires')]) ?>
    <?= $form->field($model, 'contact_gerer')->checkbox(['label'=>Yii::t('app', 'gerer les emails de contact')]) ?>
    </div>
    
    <div id="role_type_client" style="display: none ;">
    <?= $form->field($model, 'user_gerer')->checkbox(['label'=>Yii::t('app', 'gerer les employes')]) ?>
    <?= $form->field($model, 'prestataire_gerer')->checkbox(['label'=>Yii::t('app', 'gerer les prestataires')]) ?>
    <?= $form->field($model, 'evenement_gerer')->checkbox(['label'=>Yii::t('app', 'gerer les evenements')]) ?>
    </div>
        
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


<?php $this->registerJsFile(
    Url::to("/js/role_type_change.js"),
    [
        'position' => 3, // Body End
        'depends' => 'yii\bootstrap\BootstrapAsset'
    ]
);?>
