<?php

namespace app\controllers;

use Yii;
use app\models\Privilege;
use app\models\PrivilegeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\filters\VerbFilter;

/**
 * PrivilegeController implements the CRUD actions for Privilege model.
 */
class PrivilegeController extends Controller
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
     * Lists all Privilege models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->checkAutorisation('priv_gerer');
        
        $searchModel = new PrivilegeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Privilege model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->checkAutorisation('priv_gerer');
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Privilege model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->checkAutorisation('priv_gerer');
        
        $model = new Privilege();

        if ($model->load(Yii::$app->request->post())) {
            $model->cree_par = Yii::$app->user->id;
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Privilege model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->checkAutorisation('priv_gerer');
        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Privilege model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->checkAutorisation('priv_gerer');
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Privilege model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Privilege the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Privilege::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
