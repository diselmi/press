<?php

namespace app\controllers;

use Yii;
use app\models\Document;
use app\models\DocumentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Url;

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends Controller
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

    /**
     * Lists all Document models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Document model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    public function actionUploadFiles()
    {
        $path = "uploads/".md5(Yii::$app->user->identity['mail'])."/files/";
        if ( ! is_dir($path)) {
            mkdir($path);
        }
        
        if (Yii::$app->request->post()) {
            $images = UploadedFile::getInstancesByName("fichiers");
            
            foreach ($images as $image) {
                $model = new Document();
                $model->cree_par = Yii::$app->user->id;
                //$model->cree_le = new \Datetime("NOW");
                $model->cree_le = date("Y-m-d H:i:s");
                //var_dump($model->cree_le);
                //Yii::$app->end();
                $model->fichier = $image;
                if ($model->upload()) {
                    return true;
                }
                //var_dump($model->errors);
                //Yii::$app->end();
            }
            
        }
        return false;
    }

    /**
     * Creates a new Document model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $path = "uploads/".md5(Yii::$app->user->identity['mail'])."/files/";
        //var_dump($path);
        //Yii::$app->end();
        if ( ! is_dir($path)) {
            mkdir($path);
        }
        
        if (Yii::$app->request->post()) {
            $images = UploadedFile::getInstancesByName("fichiers");
            var_dump($images);
            Yii::$app->end();
            
            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
            ]);
        }
    }

    /**
     * Updates an existing Document model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
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
     * Deletes an existing Document model.
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
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Document::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
