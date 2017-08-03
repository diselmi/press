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

use yii\helpers\ArrayHelper;
use app\models\User;

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
    
    public function checkAutorisation($permission){
        $cUser = Yii::$app->user->identity; 
        if (!$cUser || !$cUser->role0->attributes[$permission]) {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden')); 
        }
        if ($cUser->role0->nom == "client" || $cUser->role0->type == "client") {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));
        }  
    }
    
    public function checkClientAutorisation($permission, $model = null)
    {
        $cUser = Yii::$app->user->identity;
        if (!$cUser) {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));
        }
        if ($cUser->role0->nom != "client" && $cUser->role0->type != "client") {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));
        }
        if (!$cUser->role0->attributes[$permission]) {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));
        }
        
        if ($model) {
            $liste = ArrayHelper::getColumn(User::getTeamOf($cUser->id), "id");
            if (!in_array($model->cree_par, $liste) ) {
                throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));
            }
        }
        
        $this->layout = "layout_client";
        
    }

    /**
     * Lists all Journaliste models.
     * @return mixed
     */
    public function actionIndex()
    {
        $cUser = Yii::$app->user->identity;
        if ($cUser && $cUser->role0->type != "client" && $cUser->role0->nom != "client" ) {
            $this->checkAutorisation('prestataire_gerer');
        }else {
            $this->checkClientAutorisation('prestataire_gerer');
        }
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
        $cUser = Yii::$app->user->identity;
        if ($cUser && $cUser->role0->type != "client" && $cUser->role0->nom != "client" ) {
            $this->checkAutorisation('prestataire_gerer');
        }else {
            $this->checkClientAutorisation('prestataire_gerer');
        }
        return $this->render('view', [
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
        $cUser = Yii::$app->user->identity;
        if ($cUser && $cUser->role0->type != "client" && $cUser->role0->nom != "client" ) {
            $this->checkAutorisation('prestataire_gerer');
        }else {
            $this->checkClientAutorisation('prestataire_gerer');
        }
        $model = new Journaliste();
        $liste_j_m = array();
        
        if ($model->load(Yii::$app->request->post()) ) {
            //var_dump(Yii::$app->request->post());
            //Yii::$app->end();
            $model->fichier_photo = UploadedFile::getInstance($model, 'fichier_photo');
            //$model['theme'] = Journaliste::$themes[$model['theme']];
            
            $model->cree_par = Yii::$app->user->id;
            
            $db = Yii::$app->db; $transaction = $db->beginTransaction();
            if ($model->save() && $model->upload()) {
                if (Yii::$app->request->post('journaliste_media')) {
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
                }
                
                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
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
        $cUser = Yii::$app->user->identity;
        $model = $this->findModel($id);
        if ($cUser && $cUser->role0->type != "client" && $cUser->role0->nom != "client" ) {
            $this->checkAutorisation('prestataire_gerer');
        }else {
            $this->checkClientAutorisation('prestataire_gerer', $model);
        }

        if ($model->load(Yii::$app->request->post()) ) {
            //var_dump(Yii::$app->request->post());
            //Yii::$app->end();
            $model->fichier_photo = UploadedFile::getInstance($model, 'fichier_photo');
            //$model['theme'] = Journaliste::$themes[$model['theme']];
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
        $cUser = Yii::$app->user->identity;
        $model = $this->findModel($id);
        if ($cUser && $cUser->role0->type != "client" && $cUser->role0->nom != "client" ) {
            $this->checkAutorisation('prestataire_gerer');
        }else {
            $this->checkClientAutorisation('prestataire_gerer', $model);
        }
        
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

        $model = $this->findModel($id);
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));
        }
        
        $models = JournalisteMedia::findAll([ "journaliste"=>$id ]);
        
        $response = $models;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
        
    }
    
    public function actionMediaSubform()
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));
        }
        $models = Media::find()->all();
        
        $response = $models;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $response;
        //var_dump($response);
    }
    
    
    
}
