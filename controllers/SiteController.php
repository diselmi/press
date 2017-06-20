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

use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use app\models\Contact;

class SiteController extends Controller
{
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
        $privileges = Privilege::find();
        
        return $this->render('index', [
            'liste_nouv'    => $this->getListeNouv(),
            'privileges'    => $privileges,
        ]);
    }
    
    public function getListeNouv()
    {
        $nouveautes = Nouveaute::find()->galleryQuery();
        /*var_dump($nouveautes);
        foreach ($nouveautes as &$nvt) {
            $nvt["href"] = $nvt["poster"];
            var_dump($nvt);
        }*/
        //$nouveautes = ArrayHelper::toArray($nouveautes, [])
        //var_dump(json_encode($nouveautes));
        //return json_encode($nouveautes);
        return $nouveautes;
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
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
            }else {
                var_dump($model->errors);
                Yii::$app->end();
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
    
    
    
    
    
}
