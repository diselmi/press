<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Journaliste */

$this->title = $model->id.": ".$model->nom;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Journalistes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$cUser = Yii::$app->user->identity;
$afficher = true;
$liste = [];
if ($cUser->role0->nom == "client" || $cUser->role0->type == "client") {
    $liste = ArrayHelper::getColumn(User::getTeamOf($cUser->id), "id");
    if (!in_array($model->cree_par, $liste) ) {
        $afficher = false;
    }
}
?>
<div class="journaliste-view">

    <div class="profile_picture_admin">
        <?php if ($model->photo){
            echo Html::img($model->photo);
        }else {
            echo Html::img(Url::to("/images/journaliste.png"));
        } ?>
    </div>
    
    <div class="profile_title_admin">
        <h1><?= ucfirst(Html::encode($this->title)) ?></h1>
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
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nom',
            'mail',
            'numtel',
            'siteweb',
            'facebook',
            'twitter',
            'theme',
            [
                'attribute' => 'cree_par',
                'value'     => function($model) {
                    return $model->creePar->getNomPrenom();
                }
            ]
        ],
    ]) ?>
    
    <h2> <?= ucfirst(Yii::t('app', 'travaille avec')) ?>: </h2>
    <?php foreach ($model->journalisteMedia as $media) { ?>
    <table class="table table-bordered">
        <tr>
            <th class="col-md-4"> <?= ucfirst($media->media0->nom) ?> </th>
            <td>
                <ul>
                <?php if($media->tv) echo "<li>".ucfirst(Yii::t('app', 'tv'))."</li>"; ?>
                <?php if($media->radio) echo "<li>".ucfirst(Yii::t('app', 'radio'))."</li>"; ?>
                <?php if($media->j_papier) echo "<li>".ucfirst(Yii::t('app', 'j_papier'))."</li>"; ?>
                <?php if($media->j_electronique) echo "<li>".ucfirst(Yii::t('app', 'j_electronique'))."</li>"; ?>
                </ul>
            </td>
        </tr>
    </table>
    <?php } ?>

</div>
