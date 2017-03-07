<?php

namespace app\controllers;

use Yii;
use app\models\Admin;
use app\models\AdminSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\helpers\ArrayHelper;
use app\models\Langue;
use app\models\Role;
use yii\web\UploadedFile;


/**
 * AdminController implements the CRUD actions for Admin model.
 */
class AdminController extends Controller
{
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

    /**
     * Lists all Admin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Admin model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        //var_dump($model->attributes);
        //$model->hasOne(Langue::className(), ['id' => 'lang'] );
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Admin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Admin();
        
        $model->imagePhoto = UploadedFile::getInstance($model, 'photo');

        if ($model->load(Yii::$app->request->post())) {
            $model->role = '2';
            $model->login = $model->mail;
            $model->pass = bin2hex(random_bytes(5));
            if ($model->save() && $model->upload()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'lang_array' => $this->getLangList(),
            'role_array' => $this->getRoleList(),
        ]);
    }

    /**
     * Updates an existing Admin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->imagePhoto = UploadedFile::getInstance($model, 'photo');
        
        if ($model->load(Yii::$app->request->post())) {
            
            //Yii::$app->end();
            //var_dump($model->imagePhoto);
            if ($model->save() && $model->upload()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        
        return $this->render('update', [
            'model' => $model,
            'lang_array' => $this->getLangList(),
            'role_array' => $this->getRoleList(),
        ]);
        
    }

    /**
     * Deletes an existing Admin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function getLangList()
    {
        $dataProvider = Langue::find();
        return ArrayHelper::map($dataProvider->asArray()->all(), 'id', 'nom');
    }
    
    protected function getRoleList()
    {
        $dataProvider = Role::find()->adminRoles();
        return ArrayHelper::map($dataProvider->asArray()->all(), 'id', 'nom');
    }
    
    
}
