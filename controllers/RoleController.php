<?php

namespace app\controllers;

use Yii;
use app\models\Role;
use app\models\RoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\filters\VerbFilter;

use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\UserSearch;

/**
 * RoleController implements the CRUD actions for Role model.
 */
class RoleController extends Controller
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
    
    public function checkAutorisation($permission, $id=null){
        $cUser = Yii::$app->user->identity;
        if (!$cUser || !$cUser->role0->attributes[$permission]) {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden')); 
        }elseif ($cUser && $id){
            if ($this->findModel($id)->ajoute_par != $cUser->id ) {
                throw new ForbiddenHttpException(Yii::t('app', 'forbidden')); 
            }
        }
    }

    /**
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Role model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->checkAutorisation('role_gerer');
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->checkAutorisation('role_gerer');
        $model = new Role();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            
            $query = User::find();
            $users_array = ArrayHelper::map($query->asArray()->all(), 'id', 'mail');
            
            //var_dump($users_array);
            //Yii::$app->end();
            
            return $this->render('create', [
                'model' => $model,
                'users_array' => $users_array,
            ]);
        }
    }

    /**
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->checkAutorisation('role_gerer');
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            
            $query = User::find();
            $users_array = ArrayHelper::map($query->asArray()->all(), 'id', 'mail');
            return $this->render('update', [
                'model' => $model,
                'users_array' => $users_array,
            ]);
        }
    }

    /**
     * Deletes an existing Role model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->checkAutorisation('role_gerer');
        //$this->findModel($id)->delete();
        $model = $this->findModel($id);
        if ($model) {
            if (empty($model->users)) {
                $model->delete();
            }else {
                //throw new NotFoundHttpException("Role has users");
                Yii::$app->session->addFlash(500, "Role has users");
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    public function actionAffectation($id)
    {
        $model = $this->findModel($id);

        if ($model !== null) {
            $searchModel = new UserSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('affecter', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        
    }
    
    public function actionAffecterAjax($id_role, $id_user)
    {
        $role = Role::findOne($id_role);
        $user = User::findOne($id_user);
        if ($role !== null && $user !== null) {
            
            $user->role = $id_role;
            if ($user->save()) {
                return $this->actionAffectation($id_role);
            }else {
                var_dump($user);
            }
            
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        
        
    }
    
    
    
    
    
    
    
}
