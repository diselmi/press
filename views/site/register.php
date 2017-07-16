<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

use app\models\Contact;

$this->title = Yii::t("app", 'Register');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('pageContent'); ?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>
    

    <div class="row">
        <div class="col-md-offset-4 col-lg-4">

            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

            <?= $form->field($model, 'nom')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'prenom') ?>

            <?= $form->field($model, 'metier')->dropDownList(Contact::listeMetiers()) ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'numtel') ?>

            <?= $form->field($model, 'adresse') ?>
            
            <?= $form->field($model, 'apropos')->textarea(['rows' => 3]) ?>
            
            <?= $form->field($model, 'entendu_par')->dropDownList(Contact::listeSources()) ?>

            <?= $form->field($model, 'etre_notifie' ,[])->checkbox()->label(Yii::t("app", "Voulez-vous être tenus au courant des nouveautés dans votre corps de métier")." ?") ?>

            <?= $form->field($model, 'etre_contacte')->checkbox()->label(Yii::t("app", "Souhaitez-vous être contacté par l’un de nos clients pour leurs différentes prestations")." ?") ?>

            <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
<?php $this->endBlock(); ?>
