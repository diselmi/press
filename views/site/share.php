<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use bigpaulie\social\share\Share;
use yii\helpers\Url;

$this->title = Yii::t('app', 'partager');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::csrfMetaTags() ?>

<div class="publication-share row">
<div class="col-md-8 col-md-offset-2" >
    <h1><?php //echo Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
        $txt = ucfirst(Yii::t('app', 'partager'));
        if( !empty($lien) ) {
            $txt = ucfirst(Yii::t('app', 'partager un autre lien'));
        }
    ?>
    
    <form id="share-form" class="" method="POST">
        <div class="input-group">
            <span id="copy_btn" class="btn input-group-addon" alt="ucfirst(Yii::t('app', 'copier le lien'))"> <i class="glyphicon glyphicon-link"></i> </span>
            <input id="lien" name="lien" type="text" class="form-control" value='<?= $lien ?>' placeholder='<?= ucfirst( Yii::t("app", 'veuillez saisir votre lien ici') ) ?>' required="required">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-info btn-flat"  ><?= $txt ?></button>
            </span>
        </div>
        
        
    </form>

    <br>
    <?php if( !empty($lien) ) { ?>
    <div class="box box-success col-md-12">    
        <div class="col-md-4" style="padding: 20px;" >
            <img style="max-width: 120px" src=<?=$image?> />
        </div>
        <div class="col-md-4">
                <?= Share::widget([
                    'type' => Share::TYPE_SMALL,
                    'tag' => 'div',
                    'template' => "<div class='btn '>{button}</div>",
                    'url'   => $lien,
                    'title' => $titre,
                    'image' => $image,
                ]) ?>
        </div>
        <div class="col-md-4">
            <img style="min-width: 150px" src="<?= Url::to(['site/qrcode']) ?>" />
        </div>
    </div>
    <br>
    
    <br>
    <div class="col-md-12" style="padding: 0px !important;">
    <div class="box box-success">
        <div class="box-header with-border text-center">
            <h3> <?= ucfirst(Yii::t('app', 'envoyer par email')) ?> </h3>
            <div class="box-tools pull-right">
                <div class="form-group">
                    <!--<div id="btn_email_add" ><h1 class="btn red"><i class="glyphicon glyphicon-plus"></i></h1></div>-->
                </div>
            </div>
        </div>
        
        <?= Html::beginForm('/site/share', 'post', ['id'=>'share_emails_form']) ?>
        <div class="form-group box-body">
            <?= Html::hiddenInput('lien', $lien) ?>
            <?= Html::hiddenInput('titre', $titre) ?>
            <?= Html::hiddenInput('image', $image) ?>
            <?= Html::textInput('objet', $titre, [
                'class'         => 'form-control',
                'placeholder'   => ucfirst(Yii::t('app', 'votre objet ici')),
            ])?>
            <?= Html::textarea('contenu', ucfirst($lien),[
                'class'         => 'form-control',
                'placeholder'   => ucfirst(Yii::t('app', 'votre contenu ici')),
                'rows'          => '6',
            ]) ?>
        </div>
        
        <div id="all_emails" class="box-body">
        </div>
        <div class="box-footer">
            <div class="form-group">
                <div id="btn_email_add" class='btn btn-success'><i class='glyphicon glyphicon-plus'></i></div>
                <?= Html::submitButton(ucfirst(Yii::t('app', 'envoyer')),[
                    'id'    => 'share_submit_btn',
                    'class' => 'btn btn-primary',
                ]) ?>
                <?= Html::endForm() ?>
            </div>
        
        </div>
    </div>
    </div>
    
    
    <?php } ?>
    
</div>
</div>

<?php $this->registerJsFile(
    Url::to("/js/share_email_add.js"),
    [
        'position' => 3, // Body End
        'depends' => 'yii\web\YiiAsset'
    ]
);?>
