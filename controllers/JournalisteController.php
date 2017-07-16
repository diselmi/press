<?php

namespace app\controllers;

use Yii;
use app\models\Journaliste;
use app\models\JournalisteSearch;
use app\models\JournalisteMedia;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use yii\web\UploadedFile;
use app\models\Media;

/**
 * JournalisteController implements the CRUD actions for Journaliste model.
 */
class JournalisteController extends Controller
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
     * Lists all Journaliste models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new JournalisteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Journaliste model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view_admin', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Journaliste model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Journaliste();
        $liste_j_m = array();
        
        if ($model->load(Yii::$app->request->post()) ) {
            //var_dump(Yii::$app->request->post());
            //Yii::$app->end();
            $model->fichier_photo = UploadedFile::getInstance($model, 'fichier_photo');
            $model['theme'] = Journaliste::$themes[$model['theme']];
            
            $db = Yii::$app->db; $transaction = $db->beginTransaction();
            if ($model->save() && $model->upload()) { 
                foreach (Yii::$app->request->post('journaliste_media') as $m){
                    $media = new JournalisteMedia();
                    $media['journaliste'] = $model->id;
                    $media['media'] = $m['id_media'];
                    $media['tv'] = isset($m['tv']) ? 1 : 0;
                    $media['radio'] = isset($m['radio']) ? 1 : 0;
                    $media['j_papier'] = isset($m['j_papier']) ? 1 : 0;
                    $media['j_electronique'] = isset($m['j_electronique']) ? 1 : 0;
                    
                    if (!$media->save()) {
                        $transaction->rollBack();
                    }
                }
                
                $transaction->commit();
                return $this->redirect(['view_admin', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'themes'=> Journaliste::allThemes()
        ]);
    }

    /**
     * Updates an existing Journaliste model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {
            //var_dump(Yii::$app->request->post());
            //Yii::$app->end();
            $model->fichier_photo = UploadedFile::getInstance($model, 'fichier_photo');
            $model['theme'] = Journaliste::$themes[$model['theme']];
            if ($model->save() && $model->upload()) {
                JournalisteMedia::deleteAll([ "journaliste"=>$id ]);
                $db = Yii::$app->db; $transaction = $db->beginTransaction();
                foreach (Yii::$app->request->post('journaliste_media') as $m){
                    $media = new JournalisteMedia();
                    $media['journaliste'] = $model->id;
                    $media['media'] = $m['id_media'];
                    $media['tv'] = isset($m['tv']) ? 1 : 0;
                    $media['radio'] = isset($m['radio']) ? 1 : 0;
                    $media['j_papier'] = isset($m['j_papier']) ? 1 : 0;
                    $media['j_electronique'] = isset($m['j_electronique']) ? 1 : 0;
                    
                    
                    if (!$media->save()) {
                        $transaction->rollBack();
                    }
                }
                
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'themes'=> Journaliste::allThemes()
        ]);

    }

    /**
     * Deletes an existing Journaliste model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        JournalisteMedia::deleteAll([ "journaliste"=>$id ]);
        $model->delete();
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the Journaliste model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Journaliste the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Journaliste::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    
    public function actionMedias($id)
    {
        $models = JournalisteMedia::findAll([ "journaliste"=>$id ]);
        
        $response = $models;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
        
    }
    
    public function actionMediaSubform()
    {
        $models = Media::find()->all();
        
        $response = $models;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
        //var_dump($response);
    }
    
    
    
}
