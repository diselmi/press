<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\RoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Roles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Role'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<?php Pjax::begin(['id'=>'pjax-role-gridview', 'enablePushState' => true]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'nom',
            'type',
            
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'affectation' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-list"></span>', $url);
                    },
                    
                ],
                'header' => Yii::t('app', 'Action'),
                'template' => '{view}{update}{delete}{affectation}',
                'visibleButtons' => [
                    'update' => function ($model_u, $key, $index) {
                        return $model_u->type == "superadmin" ? false : true;
                    },
                    'delete' => function ($model_u, $key, $index) {
                        return $model_u->type == "superadmin" ? false : true;
                    },
                    'affectation' => function ($model_u, $key, $index) {
                        return $model_u->type == "superadmin" ? false : true;
                    }
                ]
            
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
