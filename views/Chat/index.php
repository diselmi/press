<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

use kartik\widgets\FileInput;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Chat');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- Users list -->
    <div class="box box-danger">
    <div class="box-header with-border">
        <h3 class="box-title"><?= ucfirst(Yii::t("app", "liste des membres")) ?></h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body no-padding">
        <ul class="users-list clearfix">
            <?php if ($vav) { ?>
                <li>
                    <div class="chat_img_cont"><img src='<?= $vav->photo ?>' alt='<?= $vav->getNomPrenom() ?>'></div>
                    <a class="users-list-name text-green chat-user-link" data="<?= $vav->id ?>" href="#"><?= $vav->getNomPrenom() ?></a>
                    <span class="users-list-date"><?= Yii::t("app", "vis à vis") ?></span>
                </li>
            <?php } ?>
            <?php foreach ($users as $user) { ?>

            <?php if($user->role0->nom == "client" && Yii::$app->user->identity->role0->nom == "client") {$user->role0->nom = "-";} ?>
            <?php if($user->id != Yii::$app->user->id){ ?>
                <li>
                    <div class="chat_img_cont"><img src='<?= $user->photo ?>' alt='<?= $user->getNomPrenom() ?>'></div>
                    <a class="users-list-name chat-user-link" data="<?= $user->id ?>" href="#"><?= $user->getNomPrenom() ?></a>
                    <span class="users-list-date"><?= $user->role0->nom ?></span>
                </li>
            <?php }} ?>
        </ul>
        <!-- /.users-list -->
    </div>
    </div>
    
    
    
    
    
    <!-- Chat box -->
    <div id="chat-container" class="box box-success" style="display : none;">
    <div class="box-header">
        <i class="fa fa-comments-o"></i>
        <h3 class="box-title">Chat</h3>
        <div class="box-tools pull-right" data-toggle="tooltip" title="" data-original-title="Status">
            <div class="btn-group" data-toggle="btn-toggle">
            </div>
        </div>
    </div>
        
    <div class="box-body chat" id="chat-box">
        <!-- chat item -->
        
        <!-- /.item -->
    </div>
    <!-- /.chat -->
    <hr>
    <div class="box-footer">
        
        <?= Html::beginForm('/chat/send', 'post', [
            'enctype'   => 'multipart/form-data',
            'id'        => 'chat-form',
            'style'     => 'display: inline-block;'
        ]) ?>
        <?= Html::input("hidden", "destinataire", "", ['id'=>"destinataire"] ) ?>
        <div class="input-group">
            <?= Html::input("text", "chat-text", "", [
                "class" => "form-control",
                "placeholder" => Yii::t("app", "votre message ici..."),
            ]) ?>
            <div class="input-group-btn">
            <?php Modal::begin([
                'header' => "<h3>".ucfirst(Yii::t("app", "ajouter un fichier"))."</h3>",
                'toggleButton' => [
                    'label'=>"<i class='fa fa-file'></i>", 'class'=>'btn btn-success '
                ],
            ]);?>
            
            <?= FileInput::widget([
                'id'    => 'chat-file',
                'name'  => 'chat-file',
                'options'=>[
                    'language' => Yii::$app->language,
                ],
                'pluginOptions' => [
                    'uploadUrl' => Url::to(['/chat/send']),
                    'showPreview' => true,
                    'showCaption' => true,
                    'showRemove' => false,
                    'showUpload' => true,
                ],
            ]) ?>
            
            <?php Modal::end(); ?>
            </div>
            <div class="input-group-btn">
                <button type="submit" class="btn btn-success"><i class="fa fa-send"></i></button>
            </div>
        </div>
        <?= Html::endForm() ?>
        
    </div>
    </div>
    
</div>


<?php $this->registerJsFile(
    Url::to("/js/chat.js"),
    [
        'position' => 3, // Body End
        'depends' => 'yii\web\YiiAsset'
    ]
);
?>
