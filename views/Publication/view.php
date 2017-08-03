<?php

use yii\helpers\Html;
use yii\widgets\DetailView;



/* @var $this yii\web\View */
/* @var $model app\models\Publication */

$this->title = $model->titre;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Publications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="publication-view">

    <div class="profile_picture_admin">
        <?= Html::img($model->image) ?>
    </div>
    
    <div class="profile_title_admin">
        <h1><?= Html::encode($this->title) ?></h1>

        <span>
            
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
            
                <?= Html::a(Yii::t('app', 'Page'), ['page', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::beginForm('/site/share', 'post', [
                    'class' => 'form-inline',
                    'style' => 'display: inline-block;'
                ]) ?>
                <?= Html::input("hidden", "lien", yii\helpers\Url::home(true).'publication/page?id='.$model->id ) ?> 
                <?= Html::input("hidden", "titre", $model->titre ) ?> 
                <?= Html::input("hidden", "image", yii\helpers\Url::home(true).$model->image ) ?> 
                <?= Html::submitButton(ucfirst(Yii::t('app', 'partager')),[
                        'class' => 'btn btn-primary',
                    ]) ?>
                <?= Html::endForm() ?>
        </span>
        <p>
            
        
            
        
            
        </p>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'lang',
            [
                'attribute' =>  'lang',
                'label'     =>  ucfirst(Yii::t('app', 'langue')),
                'value'     =>  function($model){
                                    return $model->lang0->nom;
                                }
            ],
            'titre',
            //'type_contenu',
            //'contenu_html:ntext',
            //'contenu_doc',
            [
                'attribute' =>  'type_contenu',
                'value'     =>  function($model){
                                    return $model->type_contenu ?
                                        ucfirst(Yii::t('app', 'document')) :
                                        ucfirst(Yii::t('app', 'contenu html'));
                                }
            ],
            [
                'attribute' =>  'cree_par',
                'value'     =>  function($model){
                                    return ucfirst($model->creePar0->prenom." ".$model->creePar0->nom);
                                }
            ],
            //'image',
            //'event',
        ],
    ]) ?>
    
    <?php if (!$model->type_contenu) { ?>
        <h3> <?php //echo ucfirst(Yii::t("app", "contenu html")) ?> </h3>
        <div> <?php //echo $model->contenu_html ?> </div>
    <?php }else { ?>
    <?php } ?>
    

</div>
