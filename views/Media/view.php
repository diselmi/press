<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\models\Media;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Media */

$cUser = Yii::$app->user->identity;
$afficher = true;
$liste = [];
if ($cUser->role0->nom == "client" || $cUser->role0->type == "client") {
    $liste = ArrayHelper::getColumn(User::getTeamOf($cUser->id), "id");
    if (!in_array($model->cree_par, $liste) ) {
        $afficher = false;
    }
}


$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Media'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-view">

    <div class="profile_picture_admin">
        <?php if ($model->logo){
            echo Html::img($model->logo);
        }else {
            echo Html::img(Url::to("/images/media-photo.png"));
        } ?>
    </div>
    
    <div class="profile_title_admin">
        <h1><?= Html::encode($model->id.": ".$model->nom) ?></h1>
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
            'adresse:ntext',
            'mail',
            'numtel',
            [
                'label' => ucfirst(Yii::t("app", "types")),
                'value' => function ($model) {
                    $str = "";
                    foreach (Media::allTypes() as $key=>$type){
                        if($model->attributes[$key]) {$str .= $type.", "; };
                    }
                    return $str;
                }
                
            ],
            
            'siteweb',
            'facebook',
            'twitter',
            'date_creation',
            'pr_value',
        ],
    ]) ?>
    
    
    <h2> <?= ucfirst(Yii::t('app', 'liste des journalistes')) ?>: </h2>
    <?php foreach ($model->journalisteMedia as $media) { ?>
    <table class="table table-bordered">
        <tr>
            <th class="col-md-4"> <?= ucfirst($media->journaliste0->nom) ?> </th>
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
