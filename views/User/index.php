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
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ["view", "id"=>$key]);
                        
                        
                    },
                    'switch' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ["view", "id"=>$key]);
                        
                    },
                ],
                'template' => '{view}{update}{delete}',
            
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
