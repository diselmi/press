<?php

namespace app\controllers;

use Yii;
use app\models\Media;
use app\models\MediaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\base\Exception;
use yii\filters\VerbFilter;

use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;

use yii\helpers\ArrayHelper;
use app\models\User;

/**
 * MediaController implements the CRUD actions for Media model.
 */
class MediaController extends Controller
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
     * Lists all Media models.
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
        
        $searchModel = new MediaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Media model.
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
     * Creates a new Media model.
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
        
        $model = new Media();

        if ($model->load(Yii::$app->request->post()) ) {
            $model->fichier_logo = UploadedFile::getInstance($model, 'fichier_logo');
            $model->cree_par = Yii::$app->user->id;
            if ($model->save() && $model->upload()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'liste_types' => Media::allTypes(),
            ]);
        }
    }

    /**
     * Updates an existing Media model.
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
            $model->fichier_logo = UploadedFile::getInstance($model, 'fichier_logo');
            if ($model->save() && $model->upload()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
            'liste_types' => Media::allTypes(),
        ]);
        
    }

    /**
     * Deletes an existing Media model.
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
        
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Media::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionPrValues($id = null, $pr_value = 0)
    {
        $this->checkAutorisation('prestataire_gerer');
        /*$query = Media::find()->orderBy('type');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);*/
        $searchModel = new MediaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider->query = $dataProvider->query->orderBy('type');
        
        
        
        if ($id != null) {
            $model = Media::findOne($id);
            if ($model) {
                $model->pr_value = $pr_value;
                $model->save();
            }
            //var_dump($pr_value);
            //Yii::$app->end();
        }
        

        return $this->render('pr_values', [
            'dataProvider' => $dataProvider,
            'searchModel'   =>$searchModel,
        ]);
    }
    
}
