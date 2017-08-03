<?php

use yii\helpers\Html;
use app\assets\AppAsset;

/* @var $this yii\web\View */
/* @var $model app\models\Publication */
AppAsset::register($this);
$this->title = $model->titre;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock('pageContent'); ?>
<br><br><br><br>
<div class="col-md-offset-1 col-md-10">
    <?= $model->contenu_html ?>
    <div class="text-center">
        <span>
            <h4> <label><?= ucfirst(Yii::t("app", "publication par")).": "?> </label> <?= $model->creePar0->prenom." ".$model->creePar0->nom ?> </h4>
            <div> <label><?= ucfirst(Yii::t("app", "contact")).": "?> </label> <?= $model->creePar0->mail ?> </div>
        </span><br>
        <?= Html::img($model->creePar0->superieur0->logo) ?>
    </div><br>
    
</div>
<?php $this->endBlock(); ?>


