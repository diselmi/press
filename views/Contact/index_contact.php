<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Contacts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <br>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'type',
            'nom',
            'prenom',
            'numtel',
            'email:email',
            // 'adresse',
            // 'rs',
            'objet',
            // 'contenu',
            // 'apropos',
            // 'entendu_par',
            // 'etre_notifie',
            // 'etre_contacte',
            'envoye_le:date',
            'consulte:boolean',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{delete}',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
