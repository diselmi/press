<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SalleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Salles');
$this->params['breadcrumbs'][] = $this->title;

$cUser = Yii::$app->user->identity;
$cc = false;
$liste = [];
if ($cUser->role0->nom == "client" || $cUser->role0->type == "client") {
    $liste = ArrayHelper::getColumn(User::getTeamOf($cUser->id), "id");
    $cc = true;
}


?>
<div class="salle-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Salle'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'nom',
            'capacite',
            'connectique',
            'gouvernerat',
            'adresse',
            [
                'attribute' => 'est_premium',
                'format'      => 'boolean',
                'filter'    => Html::radioList("SalleSearch[est_premium]", null, ["0"=> Yii::t("app", "no"), "1"=>Yii::t("app", "yes")]),
            ],
            // 'vis_a_vis',
            // 'cree_par',

            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'update' => function ($model_s, $key, $index) use ($liste, $cc) {
                        if ($cc) {
                            return in_array($model_s->cree_par, $liste)  ? true : false;
                        }
                        
                    },
                    'delete' => function ($model_s, $key, $index) use ($liste, $cc) {
                        if ($cc) {
                            return in_array($model_s->cree_par, $liste)  ? true : false;
                        }
                    },
                ]
            
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
