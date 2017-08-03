<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AbonnementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Abonnements');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="abonnement-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <br>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' =>  'nom_prenom_client',
                'label'     =>  ucfirst(Yii::t('app', 'client')),
                'value'     =>  function($model){
                                    return $model->client0->nom." ".$model->client0->prenom;
                                }
            ],
            [
                'attribute' =>  'etat',
                'label'     =>  ucfirst(Yii::t('app', 'etat')),
                'value'     =>  function($model){
                                    return Yii::t('app', $model->etat);
                                }
            ],
            'date_debut:date',
            'date_fin:date',
            [
                'attribute' =>  'nom_prenom_vav',
                'label'     =>  ucfirst(Yii::t('app', 'vis a vis')),
                'value'     =>  function($model){
                                    return $model->vis_a_vis0->nom." ".$model->vis_a_vis0->prenom;
                                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'    => 'actions',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ["view", "id"=>$key], ["title" => Yii::t("app", "View") ]);

                    },
                    'relancer' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-plus-sign"></span>', ["relancer", "id"=>$key], ["title" => Yii::t("app", "Relancer") ]);
                        
                    },
                ],
                'template' => '{view}{update}{relancer}',
            
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
