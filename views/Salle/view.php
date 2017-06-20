<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Salle */

$this->title = $model->nom;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Salles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salle-view">

    <div class="profile_picture_admin">
        <?php if ($model->image){
            echo Html::img("/uploads/salles/".$model->dossier."/".$model->image);
        }else {
            echo Html::img(Url::to("/images/salle_holder.png"));
        } ?>
    </div>
    
    <div class="profile_title_admin">
        <h1><?= ucfirst(Html::encode($this->title)) ?></h1>
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
            //'id',
            'nom',
            'capacite',
            'connectique',
            'gouvernerat',
            'adresse',
            'vis_a_vis',
            'est_premium:boolean'
        ],
    ]) ?>
    
    <h3> <?= ucfirst(  Yii::t("app", "images") ); ?> </h3>
    <?= dosamigos\gallery\Gallery::widget(['items' => $liste_photos]);?>
    
    <h3> <?= ucfirst(  Yii::t("app", "documents") ); ?> </h3>
    <?php
    if ($liste_pdf) {
        foreach ($liste_pdf as $doc) {
            $href = $doc['url'];
            echo "<a href='$href' target='_blank' class='view-pdf'><img> <img src='/images/pdf_preview.png'></a>";
        }  
    }
    ?>

</div>
