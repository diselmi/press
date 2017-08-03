<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Fournisseur */

$this->title = $model->id.": ".$model->nom;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fournisseurs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$totalimages = $liste_photos ? sizeof($liste_photos) : 0 ;
$totaldocs = $liste_pdf ? sizeof($liste_pdf) : 0 ;

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
<div class="fournisseur-view">

    <div class="profile_picture_admin">
        <?php if ($model->logo){
            echo Html::img("/uploads/fournisseurs/".$model->dossier."/photos/".$model->logo);
        }else {
            echo Html::img(Url::to("/images/profile_holder_m.jpg"));
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
            'adresse:ntext',
            'numtel',
            'activite',
            'estPremium:boolean',
            //'gallery_photos',
            //'gallery_pdf',
            'siteweb',
            'facebook',
            'twitter',
        ],
    ]) ?>
    
    
    <!-- Images list -->
    <div class="box box-primary box-solid">
    <div class="box-header with-border">
        <h3 class="box-title"><?= ucfirst(  Yii::t("app", "images") ).": ".$totalimages ; ?></h3>

        <div class="box-tools pull-right">
            <button type="button" title="" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <ul class="users-list clearfix">
            <?php 
            if ($liste_photos) {
                echo dosamigos\gallery\Gallery::widget(['items' => $liste_photos]);
            }?>
        </ul>
        <!-- /.images-list -->
    </div>
    </div>
    
    <!-- Docs list -->
    <div class="box box-primary box-solid">
    <div class="box-header with-border">
        <h3 class="box-title"><?= ucfirst(  Yii::t("app", "documents") ).": ".$totaldocs ; ?></h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <ul class="users-list clearfix">
            <?php
                if ($liste_pdf) {
                   foreach ($liste_pdf as $doc) {
                      $href = $doc['url'];
                      echo "<a href='$href' title='document' target='_blank' class='view-pdf'><img src='/images/pdf_preview.png' /></a>";
                   } 
                }

            ?>
        </ul>
        <!-- /.docs-list -->
    </div>
    </div>
    

</div>
