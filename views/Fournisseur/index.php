<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\FournisseurSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Fournisseurs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fournisseur-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Fournisseur'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'nom',
            'activite',
            'siteweb',
            [
                'attribute' => 'estPremium',
                'format'      => 'boolean',
                'filter'    => Html::radioList("FournisseurSearch[estPremium]", null, ["0"=> Yii::t("app", "no"), "1"=>Yii::t("app", "yes")]),
            ],
            //'adresse:ntext',
            //'numtel',
            // 'gallery_photos',
            // 'gallery_pdf',
            // 'facebook',
            // 'twitter',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
