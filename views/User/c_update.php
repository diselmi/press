<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'User',
]) /*. $model->id*/;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['c-index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['c-view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_c_form', [
        'model' => $model,
        'lang_array'    => $lang_array,
        'role_client_array' => $role_client_array,
        'fonction_array' => $fonction_array,
    ]) ?>

</div>
