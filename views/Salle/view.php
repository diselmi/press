<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Salle */

$cUser = Yii::$app->user->identity;
$afficher = true;
$liste = [];
if ($cUser->role0->nom == "client" || $cUser->role0->type == "client") {
    $liste = ArrayHelper::getColumn(User::getTeamOf($cUser->id), "id");
    if (!in_array($model->cree_par, $liste) ) {
        $afficher = false;
    }
}

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
            //'id',
            'nom',
            'capacite',
            'connectique',
            'gouvernerat',
            'adresse',
            'vis_a_vis',
            'numtel',
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
