<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fournisseur */

$this->title = Yii::t('app', 'Create Fournisseur');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fournisseurs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fournisseur-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
