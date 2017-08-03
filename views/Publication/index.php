<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PublicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Publications');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="publication-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Publication'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' =>  'lang_search',
                'label'     =>  ucfirst(Yii::t('app', 'langue')),
                'value'     =>  function($model){
                                    return $model->lang0->nom;
                                }
            ],
            'titre',
            //'type_contenu',
            //'contenu_html:ntext',
            // 'contenu_doc',
            // 'image',
            // 'event',
            [
                'attribute' =>  'type_contenu',
                'value'     =>  function($model){
                                    return $model->type_contenu ?
                                        ucfirst(Yii::t('app', 'document')) :
                                        ucfirst(Yii::t('app', 'contenu html'));
                                }
            ],
            [
                'attribute' =>  'cree_par_search',
                'label'     =>  ucfirst(Yii::t('app', 'cree par')),
                'value'     =>  function($model){
                                    return $model->creePar0->prenom." ".$model->creePar0->nom;
                                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'page' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-file"></span>', ["page", "id"=>$key], ["title"=>"Page"]);  
                    },
                ],
                'template' => '{view}{update}{delete}{page}',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
