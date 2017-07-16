<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(['id'=>'pjax-user-gridview']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            [
                'attribute' => 'nom_role',
                'label'     => Yii::t('app', 'role'),
                'value'     => 'role0.nom'
            ],
            'nom',
            'prenom',
            'mail',
            // 'login',
            // 'pass',
            // 'lang',
            // 'role',
            // 'fonction',
            // 'logo',
            // 'adresse',
            // 'couleur_interface',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ["view", "id"=>$key],[
                                'title' => Yii::t('yii', 'view')
                            ]); 
                    },
                    'update' => function ($url, $model, $key) {
                        if ($model->role0->nom != "superadmin") {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ["update", "id"=>$key],[
                                'title' => Yii::t('yii', 'update')
                            ]);
                        }  
                    },
                    'delete' => function ($url, $model, $key) {
                        if ($model->role0->nom != "superadmin") {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ["delete", "id"=>$key],[
                                'title' => Yii::t('yii', 'delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
                                'data-method' => 'post',
                            ]);
                        }  
                    },
                    'switch' => function ($url, $model, $key) {
                        $cUser = Yii::$app->user;
                        if ($cUser->id != $key) {
                            return Html::a('<span class="glyphicon glyphicon-refresh"></span>', ["switch", "id"=>$key]);
                        }  
                    },
                ],
                'template' => '{view}{update}{delete}{switch}',
            
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
