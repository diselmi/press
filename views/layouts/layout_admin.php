<?php
    use yii\helpers\Html;
    use app\assets\AppAsset;
    use yii\widgets\Breadcrumbs;
    
    
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
        <li class="header"><span><i class="fa fa-user"></i></span> <?= ucfirst(Yii::t("app", "comptes")) ?></li>
        <li class="treeview">
          <a href="/role/index">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "roles")) ?></span>
          </a>
          
        </li>
        
        <li class="treeview">
          <a href="/user/index">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "utilisateurs")) ?></span>   
          </a>
        </li>

        </li>
        
        <li class="treeview">
          <a href="/abonnement/index">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "abonnements")) ?></span>
          </a>

        </li>
        
        <!--------------------------------->
        <li class="header"><span><i class="fa fa-users"></i></span> <?= ucfirst(Yii::t("app", "prestataires")) ?></li>
        
        <li class="treeview">
          <a href="/fournisseurs/index">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "fournisseurs")) ?></span>
          </a>
        </li>
        
        <!--------------------------------->
        <li class="header"><span><i class="fa fa-newspaper-o"></i></span> <?= ucfirst(Yii::t("app", "medias")) ?></li>
        
        <li class="treeview">
          <a href="/media/index">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "medias")) ?></span>
          </a>

        </li>
        
        <li class="treeview">
          <a href="/journaliste/index">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "journalistes")) ?></span>
          </a>

        </li>
        
        <!--------------------------------->
        <li class="header"><span><i class="fa fa-edit"></i></span> <?= ucfirst(Yii::t("app", "emails")) ?></li>
        
        <li class="treeview">
          <a href="/contact/index-contacts">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "contacts")) ?></span>          
          </a>

        </li>
        
        <li class="treeview">
          <a href="/contact/index-demandes">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "demandes")) ?></span>
          </a>
        </li>
        
        
        <!--------------------------------->
        <li class="header"><?= ucfirst(Yii::t("app", "autres")) ?></li>
        
        <li class="treeview">
          <a href="/salle/index">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "salles")) ?></span> 
          </a>
        </li>
        
        <li class="treeview">
          <a href="/temoignage/index">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "temoignages")) ?></span>
          </a>

        </li>
        
        <li class="treeview">
          <a href="/nouveaute/index">
            <span class="pull-right-container"> <i class="fa fa-angle-right pull-right"></i> </span>
            <span><?= ucfirst(Yii::t("app", "nouveautes")) ?></span>   
          </a>
        </li>
        
        <li class="treeview">
          <a href="/privilege/index">
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
<?php $this->endBody() ?>
<!-- jQuery 2.2.3 -->
<!--<script src="/lte/jquery-2.2.3.min.js"></script>-->
<!-- Bootstrap 3.3.6 -->
<!--<script src="lte/bootstrap.min.js"></script>-->
<!-- SlimScroll -->
<script src="/lte/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/lte/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/lte/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/lte/demo.js"></script>
</body>
</html>
<?php $this->endPage() ?>
