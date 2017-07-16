<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>

    <meta http-equiv="content-type" content="text/html; charset=<?= Yii::$app->charset ?>">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    <!-- Bootstrap Css -->
    <link href="bootstrap-assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Style -->
    <?php $this->head() ?>
    <link href="plugins/owl-carousel/owl.carousel.css" rel="stylesheet">
    <link href="plugins/owl-carousel/owl.theme.css" rel="stylesheet">
    <link href="plugins/owl-carousel/owl.transitions.css" rel="stylesheet">
    <link href="plugins/Lightbox/dist/css/lightbox.css" rel="stylesheet">
    <link href="plugins/Icons/et-line-font/style.css" rel="stylesheet">
    <link href="plugins/animate.css/animate.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <!-- Icons Font -->
    <link rel="stylesheet" href="css/font-awesome4.4.0.min.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <title><?= Html::encode($this->title) ?></title>
    

</head>

<body>
    <?php $this->beginBody() ?>
    <!-- Preloader
	============================================= -->
    <div class="preloader"><i class="fa fa-circle-o-notch fa-spin fa-2x"></i></div>
    <!-- Header
	============================================= -->
    <section class="main-header">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><img src="img/logo/logo.png" class="img-responsive" alt="logo"></a>
                </div>
                <div class="collapse navbar-collapse text-center" id="bs-example-navbar-collapse-1">
                    <div class="col-md-8 col-xs-12 nav-wrap">
                        <?php
                        echo Nav::widget([
                            'options' => ['class' => 'navbar-nav nav'],
                            'items' => [
                                /*['label' => 'Home', 'url' => ['/site/index'], 'linkOptinos'=>'page-scroll'],
                                ['label' => 'About', 'url' => ['/site/about'], 'linkOptinos'=>'page-scroll'],
                                ['label' => 'Contact', 'url' => ['/site/contact'], 'linkOptinos'=>'page-scroll'],*/
                                ['label' => 'Home', 'url' => ['/#owl-hero'], 'linkOptinos'=>'page-scroll'],
                                ['label' => 'Services', 'url' => ['/#services'], 'linkOptinos'=>'page-scroll'],
                                ['label' => 'News', 'url' => ['/#portfolio'], 'linkOptinos'=>'page-scroll'],
                                ['label' => 'About', 'url' => ['/#team'], 'linkOptinos'=>'page-scroll'],
                                ['label' => 'Contact', 'url' => ['/contact'], 'linkOptinos'=>'page-scroll'],
                            ],
                        ]);
                        //NavBar::end();
                        ?>
                    </div>
                    <div class="social-media hidden-sm hidden-xs">
                        <?php
                        echo Nav::widget([
                            'options' => ['class' => 'navbar-nav nav'],
                            'items' => [
                                //"<li><a href='#'><i class='fa fa-facebook'></i></a></li>",
                                Yii::$app->user->isGuest ? (
                                    ['label' => 'Login', 'url' => ['/site/login']]
                                ) : (
                                    '<li>'
                                    . Html::beginForm(['/site/logout'], 'post')
                                    . Html::submitButton(
                                        '<i class="fa fa-sign-out"></i>',
                                        [   'class' => 'btn btn-link logout',
                                            'style' => 'padding: 30px 15px;'
                                        ]
                                    )
                                    . Html::endForm()
                                    . '</li>'
                                )
                                
                            ],
                        ]);
                        //NavBar::end();
                        ?>
                    </div>
                </div>
            </div>
        </nav>

    <?php if (isset($this->blocks['indexHeaderSlide'])){
        echo $this->blocks['indexHeaderSlide'];
    } ?>
        
    </section>
    

    <div class="container">
        <!--<?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>-->
        
        <?php if (isset($this->blocks['pageContent'])){
            echo $this->blocks['pageContent'];
        } ?>

        <?= $content ?>
    </div>

    <?php if (isset($this->blocks['indexSections'])){
        echo $this->blocks['indexSections'];
    } ?>
    
    <!-- Footer
	============================================= -->
    <footer>
        <div class="container">
            <h1>PR Application</h1>
            <div class="social">
                <a href="#"><i class="fa fa-facebook fa-2x"></i></a>
                <a href="#"><i class="fa fa-twitter fa-2x"></i></a>
                <a href="#"><i class="fa fa-dribbble fa-2x"></i></a>
            </div>
            <h6>&copy; 2017 Agence Inspire</h6>
        </div>
    </footer>
    <?php $this->endBody() ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery1.11.3.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap-assets/js/bootstrap.min.js"></script>
    <script src="js/custom.js"></script>
    <!-- JS PLUGINS -->
    <script src="plugins/owl-carousel/owl.carousel.min.js"></script>
    <script src="js/jquery.easing.min.js"></script>
    <script src="plugins/waypoints/jquery.waypoints.min.js"></script>
    <script src="plugins/countTo/jquery.countTo.js"></script>
    <script src="plugins/inview/jquery.inview.min.js"></script>
    <script src="plugins/Lightbox/dist/js/lightbox.min.js"></script>
    <script src="plugins/WOW/dist/wow.min.js"></script>
    <!-- GOOGLE MAP -->
    <!--<script src="https://maps.googleapis.com/maps/api/js"></script> -->
    
</body>
</html>
<?php $this->endPage() ?>