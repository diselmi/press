<?php

namespace app\controllers;

use Yii;
use app\models\Abonnement;
use app\models\AbonnementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use app\models\User;

/**
 * AbonnementController implements the CRUD actions for Abonnement model.
 */
class AbonnementController extends Controller
{
    public $layout = "layout_admin";
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    public function checkAutorisation($permission, $id=null){
        $cUser = Yii::$app->user->identity;
        if ($cUser && $id && ($this->findModel($id)->cree_par != $cUser->id) ){
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden')); 
        }elseif (!$cUser || !$cUser->role0->attributes[$permission]) {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden')); 
        }
    }

    /**
     * Lists all Abonnement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AbonnementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Abonnement model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Abonnement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        //$liste_admins = User::find()->onlyAdmins()->all();
        //$liste_admins = ArrayHelper::map($liste_admins, 'id', 'nom');
        
        $liste_admins = $this->preparerListeAdmins();
        $liste_etats = $this->preparerListeEtats();
        
        //var_dump($liste_etats);
        //Yii::$app->end();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'liste_admins' => $liste_admins,
                'liste_etats' => $liste_etats,
            ]);
        }
    }

    /**
     * Finds the Abonnement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Abonnement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Abonnement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    
    
    
    protected function preparerListeEtats()
    {
        $liste_etats = User::find()->onlyAdmins()->all();
        $liste_etats = Yii::$app->db->createCommand("SHOW COLUMNS FROM abonnement WHERE Field = 'etat' ; ")->queryAll()[0]['Type'];
        $liste_etats = explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2", $liste_etats));
        //$liste_etats_keys = array_flip($liste_etats);
        $liste_etats_keys = $liste_etats;
        $liste_etats_values = array_map(array($this,'preparerValues'), $liste_etats);
        
        //return $liste_etats_keys;
        return array_combine($liste_etats_keys, $liste_etats_values);
    }
    
    public function preparerValues($v)
    {
        return Yii::t("app", $v);
    }
    
    
    protected function preparerListeAdmins()
    {
        $liste_admins = User::find()->onlyAdmins()->all();
        //$liste_etats_values = array_map(array($this,'preparerValuesAdmins'), $liste_admins);
        $liste_admins = ArrayHelper::map($liste_admins, 'id', 'mail');
        
        return $liste_admins;
    }
    
    
    
    
}
