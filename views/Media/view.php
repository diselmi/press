<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\models\Media;

/* @var $this yii\web\View */
/* @var $model app\models\Media */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Media'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-view">

    <div class="profile_picture_admin">
        <?= Html::img($model->logo) ?>
    </div>
    
    <div class="profile_title_admin">
        <h1><?= Html::encode($model->id.": ".$model->nom) ?></h1>
        <p>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
