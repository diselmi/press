<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use yii\widgets\ActiveForm;
use app\models\Media;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MediaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('app', 'Media');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Media'), 'url' => ['index']];
$this->params['breadcrumbs'][] = 'PR Values';
?>
<div class="media-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
<div class="media-form">
    <?php //$form = ActiveForm::begin(); ?>
    
    
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'nom',
            [
                'label' => ucfirst(Yii::t("app", "types")),
                'value' => function ($model) {
                    $str = "";
                    foreach (Media::allTypes() as $key=>$type){
                        if($model->attributes[$key]) {$str .= $type.", "; };
                    }
                    return $str;
                }
                
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'PR Value',
                'buttons' => [
                    'change_pr' => function ($url, $model, $key) {
                        //return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ["view", "id"=>$key]);
                        //$affichage .= $form->field($model, 'id', ['inputOptions' => ['style'=>'display: none;' ]])->input('hidden')->label(false);
                        //$affichage .= $form->field($model, 'pr_value', ['options' => ['class'=>'form-froup col-sm-10' ]])->input('number')->label(false);
                        $affichage = "<form >";
                        $affichage .= "<div class='col-sm-10'>".Html::input('hidden', 'id',$model->id );
                        $affichage .= Html::input('number', 'pr_value', $model->pr_value, ['class' => 'form-control']);
                        $affichage .= "</div>";
                        $affichage .= Html::submitButton(Yii::t('app', 'Change'), ['class' => 'btn btn-primary']);
                        $affichage .= "</form>";
                        
                        return $affichage;
                    },
                ],
                'template' => '{change_pr}',
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?>

    <?php //ActiveForm::end(); ?>

</div>

</div>
