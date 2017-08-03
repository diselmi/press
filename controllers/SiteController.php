<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

use app\models\LoginForm;
use app\models\ContactForm;

use app\models\User;
use app\models\Nouveaute;
use app\models\Privilege;
use app\models\Temoignage;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use app\models\Contact;

use dosamigos\qrcode\formats\MailTo;
use dosamigos\qrcode\QrCode;

class SiteController extends Controller
{
    //public $layout = "main.php";
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        //Yii::$app->user->switchIdentity(User::findIdentity(3));
        //var_dump(Yii::$app->user);
        //Yii::$app->end();
        
        $privileges = Privilege::find()->all();
        $temoignages = Temoignage::find()->all();
        
        return $this->render('index', [
            'liste_nouv'    => Nouveaute::find()->galleryQuery(),
            'privileges'    => $privileges,
            'temoignages'    => $temoignages,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        //$this->layout = "layout_admin";
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(Url::toRoute("/dashboard"));
            //return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new Contact(['scenario' => Contact::SCENARIO_CONTACT]);
        //$model->contact(Yii::$app->params['adminEmail']) // send email
        
        
        if ($model->load(Yii::$app->request->post())) {
            $model->type = 2;
            if ($model->save()) {
                //Yii::$app->session->setFlash('contactFormSubmitted');
                return $this->redirect(Url::home());
            }
        }
        
        return $this->render('contact', [
            'model' => $model,
        ]);
    }
    
    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionSubscription()
    {   
        $model = new Contact(['scenario' => Contact::SCENARIO_REGISTER]);
        
        
        if ($model->load(Yii::$app->request->post())) {
            $model->type = 3;
            if ($model->save()) {
                //Yii::$app->session->setFlash('contactFormSubmitted');
                return $this->redirect(Url::home());
            }else {
                var_dump($model->errors);
                Yii::$app->end();
            }
        }
        
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->identity->switchWith(3);
        }
        
        return $this->render('about');
    }
    
    /**
     * redirect to Dashboard.
     *
     * @return string
     */
    public function actionDashboard()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(Url::toRoute("/login"));
        }
        $user = Yii::$app->user->identity;
        if ($user->role0->nom == "client" || $user->role0->type == "client") {
            $this->layout = "layout_client";
            return $this->render('index_client');
        }elseif ($user->role0->type == "admin") {
            $this->layout = "layout_admin";
            return $this->render('index_admin');
        }else {
            return $this->goHome();
        }
    }
    
    /**
     * shares a content.
     * @param integer $lien
     * @param array $emails
     * @return string
     */
    public function actionShare()
    {
        $this->layout = "layout_client";
        
        $titre = "";
        $image = "";
        $lien = "";
        $post = Yii::$app->request->post();
        if (isset($post["emails"])) {
            $emails = $post["emails"];
            $objet = isset($post["objet"]) ? $post["objet"] : "";
            $contenu = isset($post["contenu"]) ? $post["contenu"] : "";
            if ($this->envoyerEmails($emails, $objet, $contenu)) {
                Yii::$app->session->setFlash("success", Yii::t("app", "Votre email a été envoyé."));
            }else {
                Yii::$app->session->setFlash("error", Yii::t("app", "Une erreur s'est produite. Votre email n'a pas été envoyé."));
            }
            
            //var_dump($post);
            //Yii::$app->end();
            
        }
        if (isset($post["lien"]) && $lien = $post["lien"]) {
            Yii::$app->session->set("tmp_lien", $lien);
        }
        if (isset($post["titre"]) ) {
            $titre = $post["titre"];
        }
        if (isset($post["image"]) ) {
            $image = $post["image"];
        }
        
        return $this->render('share', [
            'lien'          => $lien,
            'titre'         => $titre,
            'image'         => $image,
        ]);
    }
    
    private function envoyerEmails($emails, $obj, $msg)
    {
        foreach ($emails as $email) {
            try {
                Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($email)
                //->setReplyTo([$this->email => $this->prenom." ".$this->nom." (".$this->rc.")"])
                ->setSubject($obj)
                ->setTextBody($msg)
                ;//->send();
            } catch (\Exception $ex) {
                return false;
            }
            
            //var_dump(Yii::$app->mailer);
            //Yii::$app->end();
        }
        return false;
    }
    
    public function actionQrcode() {
        return QrCode::png(Yii::$app->session->get('tmp_lien') );
    }
    
    
    
    
    
}
