<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Journaliste */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Journalistes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$cUser = Yii::$app->user->identity;
$afficher = false;
$liste = [];
if ($cUser->role0->nom == "client" || $cUser->role0->type == "client") {
    $liste = ArrayHelper::getColumn(User::getTeamOf($cUser->id), "id");
    if (in_array($model->cree_par, $liste) ) {
        $afficher = true;
    }
}

?>
<div class="journaliste-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if($afficher) echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        if($afficher) echo Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
            'id',
            'nom',
            'mail',
            'numtel',
            'photo',
            'siteweb',
            'facebook',
            'twitter',
        ],
    ]) ?>

</div>
