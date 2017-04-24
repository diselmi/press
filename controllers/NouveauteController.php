<?php

namespace app\controllers;

use Yii;
use app\models\Nouveaute;
use app\models\Document;
use app\models\NouveauteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\UploadedFile;

/**
 * NouveauteController implements the CRUD actions for Nouveaute model.
 */
class NouveauteController extends Controller
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
     * Lists all Nouveaute models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NouveauteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Nouveaute model.
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
     * Creates a new Nouveaute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Nouveaute();
        
        $model->fichier_image = UploadedFile::getInstance($model, 'image');
        //var_dump($model->fichier_image);
        //Yii::$app->end();

        if ($model->load(Yii::$app->request->post())) {
            $model->cree_par = Yii::$app->user->id;
            $model->cree_le = date("Y-m-d H:i:s");
            
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
     * Updates an existing Nouveaute model.
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
     * Deletes an existing Nouveaute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model && file_exists("./".$model->image)) {
            unlink("./".$model->image);
        }
        
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Nouveaute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Nouveaute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Nouveaute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
