<?php

namespace app\controllers;

use Yii;
use app\models\Publication;
use app\models\PublicationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

use app\models\Document;
use app\models\Langue;
use app\models\Gallery;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;

use yii\web\Response;

/**
 * PublicationController implements the CRUD actions for Publication model.
 */
class PublicationController extends Controller
{
    public $layout = "layout_client";
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
    
    public function checkClientAutorisation($permission)
    {
        $cUser = Yii::$app->user->identity;
        if (!$cUser || Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));
        }
        if ($cUser->role0->nom != "client" && $cUser->role0->type != "client") {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));
        }
        if (!$cUser->role0->attributes[$permission]) {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));
        }
        
    }
    
    public function beforeAction($action) {
        if($action->id == 'upload-editor-image') {
            Yii::$app->request->enableCsrfValidation = false;
        }else {
            Yii::$app->request->enableCsrfValidation = true;
        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all Publication models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->checkClientAutorisation('evenement_gerer');
        $searchModel = new PublicationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, Yii::$app->user->id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Publication model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->checkClientAutorisation('evenement_gerer');
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    public function actionPage($id)
    {
        $this->layout = "main.php";
        $model = $this->findModel($id);
        if ($model && $model->type_contenu == 1 && $model->contenuDoc) {
            $chemin = "/uploads/".md5(Yii::$app->user->identity->mail)."/publication-files/".md5($id)."/".$model->contenuDoc->chemin;
            return $this->redirect($chemin);
        }
        return $this->render('page', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Publication model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->checkClientAutorisation('evenement_gerer');
        $model = new Publication();

        if ($model->load(Yii::$app->request->post())) {
            
            //var_dump($model->contenu_html);
            //Yii::$app->end();
            $model->cree_par = Yii::$app->user->id;
            $model->champ_doc = UploadedFile::getInstance($model, 'champ_doc');
            $model->champ_image = UploadedFile::getInstance($model, 'champ_image');
            
            $db = Yii::$app->db; $transaction = $db->beginTransaction();
            if ($model->save()) {
                if ($model->type_contenu !== null) {
                    if ($model->type_contenu == 1 && $model->uploadDoc() && $model->uploadImage()) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }elseif($model->type_contenu == 0) {
                        $transaction->commit();
                        $model->moveHtmlImages();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }else {
                        $transaction->rollBack();
                        var_dump($model->uploadImage());
                        Yii::$app->end();
                    }     
                }
            }else {
                $transaction->rollBack();
            }
            
        }
        return $this->render('create', [
            'model' => $model,
            'lang_array' => $this->getLangList(),
        ]);
    }

    /**
     * Updates an existing Publication model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->checkClientAutorisation('evenement_gerer');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->champ_doc = UploadedFile::getInstance($model, 'champ_doc');
            $model->champ_image = UploadedFile::getInstance($model, 'champ_image');
            //$model->cree_par = Yii::$app->user->id;
            
            $db = Yii::$app->db; $transaction = $db->beginTransaction();
            if ($model->save()) {
                if ($model->contenu_doc && !$model->type_contenu) {
                    $model->contenuDoc->delete();
                    $model->save();
                }
                if ($model->type_contenu !== null) {
                    if ($model->uploadDoc() && $model->uploadImage()) {
                        $transaction->commit();
                        $model->moveHtmlImages();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }else {
                        $transaction->rollBack();
                        //var_dump($model->uploadDoc());
                        //Yii::$app->end();
                    }   
                }
            }else {
                $transaction->rollBack();
            }
        
        }
        
        return $this->render('update', [
            'model' => $model,
            'lang_array' => $this->getLangList(),
        ]);
    }

    /**
     * Deletes an existing Publication model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->checkClientAutorisation('evenement_gerer');
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Publication model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Publication the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Publication::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function getLangList() {
        $dataProvider = Langue::find();
        return ArrayHelper::map($dataProvider->asArray()->all(), 'id', 'nom');
    }
    

    
    
    public function actionUploadEditorImage() {
        $this->checkClientAutorisation('evenement_gerer');
        
        $response = [
            'uploaded' => 0,
            'filename' => '',
            'url' => '',
            'error' => ''
        ];

        if (isset($_FILES["upload"])) {
            
            $target_dir = "uploads/".md5(Yii::$app->user->identity->mail)."/publication-files/tmp/";
            $target_file = $target_dir . basename($_FILES["upload"]["name"]);

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777);
            }
            if(move_uploaded_file($_FILES["upload"]["tmp_name"], "./".$target_file )) {
                $response['uploaded'] = 1;
                $response['filename'] = $_FILES["upload"]["name"];
                $response['url'] = "/".$target_file;
                
            } else {
                $response['error'] = $_FILES["error"];
            }
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $response;
    }
    
    
}
