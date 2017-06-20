<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Contact */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Demandes'), 'url' => ['index-demandes']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'type',
            'nom',
            'prenom',
            'numtel',
            'email:email',
            //'adresse',
            //'rs',
            //'objet',
            //'contenu',
            //'apropos',
            //'entendu_par',
            //'etre_notifie',
            //'etre_contacte',
            'envoye_le:date',
            'consulte:boolean',
        ],
    ]) ?>
    
    <br>
    <h3> <?php echo ucfirst( Yii::t("app", "repondre")." :" ) ?> </h3>
    <?php $form = ActiveForm::begin(['id'=>"reponse-demande-form", "method" =>"POST"]); ?>
    <?= Html::textarea("reponse", "", ['rows'=>5, "class"=>"form-control"]) ?> <br>
    <?= Html::submitButton(Yii::t("app", "repondre"), ["class"=>"btn btn-primary"]) ?>
        
    <?php ActiveForm::end(); ?>

</div>
