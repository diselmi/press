<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Role */

$this->title = Yii::t('app', 'Affectation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-affectation">

    <h1><?= Html::encode($this->title.': '.$model->nom) ?></h1>
   
        
        
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'm_superieur',
                'label' => ucfirst( Yii::t("app", "mail du superieur") ),
                'value' => function(User $model){
                    return $model->superieur0->mail;
                }
            ],
            'mail',
            'nom',
            'prenom',
            'role0.nom',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'affecter_ajax' => function ($url, $model_user, $key) use ($model) {
                        if ($model_user->role == $model->id) {
                            $lien = Url::toRoute(['affecter-ajax', 'id_role'=>$model->id, 'id_user' => $key, 'affectation' => 0 ]);
                            //return Html::tag('span', '', ['class' => 'glyphicon glyphicon-ok', 'style' => 'color:#3c763d;']);
                            return "<span style='color: grey ;' class='glyphicon glyphicon-ok'></span>";
                        }elseif($model_user->role0->nom == "client") {
                            $lien = Url::toRoute(['affecter-ajax', 'id_role'=>$model->id, 'id_user' => $key ]);
                            return '<span style="color:#ccc;" class="glyphicon glyphicon-ban-circle disabled"></span>';
                        }else {
                            $lien = Url::toRoute(['affecter-ajax', 'id_role'=>$model->id, 'id_user' => $key ]);
                            return Html::a('<span class="glyphicon glyphicon-check"></span>',$lien,['title' => Yii::t('app', 'Affecter'), 'data-pjax'=>'w0',]);
                        }

                    },
                ],
                'template' => '{affecter_ajax}',

            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>


</div>
