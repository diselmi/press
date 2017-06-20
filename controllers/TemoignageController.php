<?php

namespace app\controllers;

use Yii;
use app\models\Temoignage;
use app\models\TemoignageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\UploadedFile;

/**
 * TemoignageController implements the CRUD actions for Temoignage model.
 */
class TemoignageController extends Controller
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
     * Lists all Temoignage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TemoignageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Temoignage model.
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
     * Creates a new Temoignage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Temoignage();
        
        $model->fichier_image = UploadedFile::getInstance($model, 'image');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save() && $model->upload()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }else {
                //var_dump($model->attributes);
                //Yii::$app->end();
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Temoignage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $model->fichier_image = UploadedFile::getInstance($model, 'image');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save() && $model->upload()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }else {
                //var_dump($model->attributes);
                //Yii::$app->end();
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Temoignage model.
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
     * Finds the Temoignage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Temoignage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Temoignage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
