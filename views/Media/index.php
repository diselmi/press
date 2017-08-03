<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use yii\helpers\ArrayHelper;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel app\models\MediaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Media');
$this->params['breadcrumbs'][] = $this->title;

$cUser = Yii::$app->user->identity;
$cc = false;
$liste = [];
if ($cUser->role0->nom == "client" || $cUser->role0->type == "client") {
    $liste = ArrayHelper::getColumn(User::getTeamOf($cUser->id), "id");
    $cc = true;
}

?>
<div class="media-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Media'), ['create'], ['class' => 'btn btn-success']) ?>
        <?php
            if ($cUser->role0->nom != "client" && $cUser->role0->type != "client") {
                echo Html::a(Yii::t('app', 'PR Values'), ['pr-values'], ['class' => 'btn btn-primary']);
            }
        ?>
        
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'nom',
            //'adresse:ntext',
            'mail',
            'numtel',
            'pr_value',
            [
                'attribute' => 'tv',
                'format'      => 'boolean',
                'filter'    => Html::radioList("MediaSearch[tv]", null, ["0"=> Yii::t("app", "no"), "1"=>Yii::t("app", "yes")]),
            ],
            [
                'attribute' => 'radio',
                'format'      => 'boolean',
                'filter'    => Html::radioList("MediaSearch[radio]", null, ["0"=> Yii::t("app", "no"), "1"=>Yii::t("app", "yes")]),
            ],
            [
                'attribute' => 'j_papier',
                'format'      => 'boolean',
                'filter'    => Html::radioList("MediaSearch[j_papier]", null, ["0"=> Yii::t("app", "no"), "1"=>Yii::t("app", "yes")]),
            ],
            [
                'attribute' => 'j_electronique',
                'format'      => 'boolean',
                'filter'    => Html::radioList("MediaSearch[j_electronique]", null, ["0"=> Yii::t("app", "no"), "1"=>Yii::t("app", "yes")]),
            ],
            // 'logo',
            // 'siteweb',
            // 'facebook',
            // 'twitter',
            // 'date_creation',

            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'update' => function ($model_s, $key, $index) use ($liste, $cc) {
                        if ($cc) {
                            return in_array($model_s->cree_par, $liste)  ? true : false;
                        }
                        return true;
                        
                    },
                    'delete' => function ($model_s, $key, $index) use ($liste, $cc) {
                        if ($cc) {
                            return in_array($model_s->cree_par, $liste)  ? true : false;
                        }
                        return true;
                    },
                ]
            
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
