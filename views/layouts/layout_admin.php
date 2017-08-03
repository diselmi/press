<?php
    use yii\helpers\Html;
    use app\assets\AppAsset;
    use yii\widgets\Breadcrumbs;
   use yii\bootstrap\Alert;
    
    use app\models\Message;
    
    AppAsset::register($this);
    
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | <?= Html::encode($this->title) ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?= Html::csrfMetaTags() ?>
  <?php $this->head() ?>
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/lte/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/lte/font-awesome4.5.0.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="/lte/ionicons2.0.1.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/lte/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="/lte/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="/" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>PR</b>.A</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>PR</b>.ADMIN</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <li class="dropdown messages-menu text-red">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <?php
                $totalMessagesNonLus = sizeof(Message::find()->messagesNonLus());
              ?>
              <span class="label label-danger"><?= $totalMessagesNonLus ?></span>
            </a>
            <ul class="dropdown-menu">
                <li class="header"><?= "<b>".$totalMessagesNonLus."</b> ".Yii::t("app", "messages non lus") ?></li>
              <li>
                <!-- inner menu: contains the actual data -->
                <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 200px;"><ul class="menu" style="overflow: hidden; width: 100%; height: 200px;">
                  
                    <?php foreach (Message::find()->messagesNonLus() as $message) { ?>
                    <li><!-- start message -->
                        <a href="/chat/index">
                            <div class="pull-left">
                                <img src=<?= $message->expediteur0->photo ?> class="img-circle" alt="User Image">
                            </div>
                            <h4>
                                <?= $message->expediteur0->prenom." ".$message->expediteur0->nom ?>
                                <small><i class="fa fa-clock-o"></i> 5 mins</small>
                            </h4>
                            <p><?= $message->texte=="." ? Yii::t("app", "document"): $message->texte ?></p>
                        </a>
                    </li>        

                    <?php } ?>
                  
                </ul><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px;"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
              </li>
              <li class="footer"><a href="/chat/index"><?= Yii::t("app", "consulter tous les messages") ?></a></li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="/user/profile" class="dropdown-toggle" data-toggle="dropdown">
              <img src=<?= Yii::$app->user->identity->photo ?> class="user-image" alt="User Image">
              <span class="hidden-xs"><?= ucfirst(Yii::$app->user->identity->login) ?></span>
            </a>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <!--<a href="/site/logout" data-toggle="control-sidebar"><i class="fa fa-sign-out"></i></a>-->
          </li>
          <li>
            <?php 
                echo Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    '<i class="fa fa-sign-out"></i>',
                    ['class' => 'btn btn-link text-yellow logout']
                )
                . Html::endForm()
            ?>
          </li>
          
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src=<?= Yii::$app->user->identity->photo ?> class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?= ucfirst(Yii::$app->user->identity->prenom)." ".ucfirst(Yii::$app->user->identity->nom) ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> <?= Yii::$app->user->identity->role0->nom ?></a>
        </div>
      </div>
      
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
          
        <!--------------------------------->
        <?php
            $disabled="";
            if (!Yii::$app->user->identity->role0->attributes['user_gerer']) { $disabled="disabled";  }
        ?>
        <li class="header"><span><i class="fa fa-user"></i></span> <?= ucfirst(Yii::t("app", "comptes")) ?></li>
        <li class="treeview">
          <a href="/role/index" class="<?= $disabled ?>">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "roles")) ?></span>
          </a>
          
        </li>
        
        <li class="treeview">
          <a href="/user/index" class="<?= $disabled ?>">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "utilisateurs")) ?></span>   
          </a>
        </li>

        </li>
        
        <li class="treeview">
          <a href="/abonnement/index" class="<?= $disabled ?>">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "abonnements")) ?></span>
          </a>

        </li>
        
        <li class="treeview">
          <a href="/chat/index" class="<?= $disabled ?>">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "chat")) ?></span>
          </a>

        </li>
        
        <!--------------------------------->
        <?php
            $disabled="";
            if (!Yii::$app->user->identity->role0->attributes['prestataire_gerer']) { $disabled="disabled";  }
        ?>
        <li class="header"><span><i class="fa fa-users"></i></span> <?= ucfirst(Yii::t("app", "prestataires")) ?></li>
        
        <li class="treeview">
          <a href="/fournisseur/index" class="<?= $disabled ?>">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "fournisseurs")) ?></span>
          </a>
        </li>
        
        <!--------------------------------->
        <li class="header"><span><i class="fa fa-newspaper-o"></i></span> <?= ucfirst(Yii::t("app", "medias")) ?></li>
        
        <li class="treeview">
          <a href="/media/index" class="<?= $disabled ?>">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "medias")) ?></span>
          </a>

        </li>
        
        <li class="treeview">
          <a href="/journaliste/index" class="<?= $disabled ?>">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "journalistes")) ?></span>
          </a>

        </li>
        
        <!--------------------------------->
        <?php
            $disabled="";
            if (!Yii::$app->user->identity->role0->attributes['contact_gerer']) { $disabled="disabled";  }
        ?>
        <li class="header"><span><i class="fa fa-edit"></i></span> <?= ucfirst(Yii::t("app", "emails")) ?></li>
        
        <li class="treeview">
          <a href="/contact/index-contacts" class="<?= $disabled ?>">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "contacts")) ?></span>          
          </a>

        </li>
        
        <li class="treeview">
          <a href="/contact/index-demandes" class="<?= $disabled ?>">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "demandes")) ?></span>
          </a>
        </li>
        
        
        <!--------------------------------->
        <li class="header"><?= ucfirst(Yii::t("app", "autres")) ?></li>
        
        <?php
            $disabled="";
            if (!Yii::$app->user->identity->role0->attributes['prestataire_gerer']) { $disabled="disabled";  }
        ?>
        <li class="treeview">
          <a href="/salle/index" class="<?= $disabled ?>">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "salles")) ?></span> 
          </a>
        </li>
        
        <?php
            $disabled="";
            if (!Yii::$app->user->identity->role0->attributes['site_gerer']) { $disabled="disabled";  }
        ?>
        <li class="treeview">
          <a href="/temoignage/index" class="<?= $disabled ?>">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "temoignages")) ?></span>
          </a>

        </li>
        
        <li class="treeview">
          <a href="/nouveaute/index" class="<?= $disabled ?>">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "nouveautes")) ?></span>   
          </a>
        </li>
        
        <li class="treeview">
          <a href="/privilege/index" class="<?= $disabled ?>">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "privileges")) ?></span>   
          </a>
        </li>
        
        
        
        <!--------------------------------->
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    </section>

    <!-- Main content -->
    <section class="content">

        <?= $content ?>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 0.0.1
    </div>
    <strong>&copy; 2017 <a href="#">Agence Inspire</a>.</strong>
  </footer>

</div>
<!-- ./wrapper -->
<!-- jQuery 2.2.3 -->
<script src="/lte/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/lte/bootstrap.min.js"></script>
<?php $this->endBody() ?>

<!-- SlimScroll -->
<script src="/lte/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/lte/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/lte/app.min.js"></script>
</body>
</html>
<?php $this->endPage() ?>
