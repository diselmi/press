<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\models\Langue;
use app\models\Role;
use app\models\Fonction;
use yii\web\UploadedFile;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex() {
        
        $this->checkAutorisation('user_gerer');
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index_admin', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $this->checkAutorisation('user_gerer');
        $model = $this->findModel($id);
        if ($model->role0->nom == 'client') {
            return $this->render('view', [
                'model' => $model,
            ]);
        }else {
            return $this->render('view_admin', [
                'model' => $model,
            ]);
        }
        
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->checkAutorisation('user_gerer');
        $model = new User();

        $model->imagePhoto = UploadedFile::getInstance($model, 'photo');
        $model->imageLogo = UploadedFile::getInstance($model, 'logo');

        if ($model->load(Yii::$app->request->post())) {
            $model->login = $model->mail;
            $model->pass = bin2hex(random_bytes(5));
            if ($this->validateFonctionField(Yii::$app->request->post(), $model)) {

                if ($model->save() && $model->upload()) {
                    return $this->redirect(['view_admin', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
                    'model' => $model,
                    'lang_array' => $this->getLangList(),
                    'role_array' => $this->getRoleList(),
                    'role_admin_array' => $this->getRoleList("nom", 1), // admin only
                    'fonction_array' => $this->getFonctionList(),
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->checkAutorisation('user_gerer');
        
        $model = $this->findModel($id);

        $model->role_type = $model->role0->type;

        $model->imagePhoto = UploadedFile::getInstance($model, 'photo');
        $model->imageLogo = UploadedFile::getInstance($model, 'logo');

        if ($model->load(Yii::$app->request->post())) {

            // verifier Couleur interface
            if ($model->couleur_interface && (strlen($model->couleur_interface) != 7) && !ctype_xdigit($model->couleur_interface)) {
                $model->couleur_interface = null;
            }

            // Valider champ Fonction
            if ($this->validateFonctionField(Yii::$app->request->post(), $model)) {
                //var_dump(Yii::$app->request->post());
                //Yii::$app->end();
                if ($model->save() && $model->upload()) {
                    //var_dump(Yii::$app->user->identity->role0);
                    //Yii::$app->end();
                    
                    if ($cUser && $cUser->role0->type == 'client') {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }else {
                        return $this->redirect(['view_admin', 'id' => $model->id]);
                    }
                    
                }
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'lang_array' => $this->getLangList(),
                    'role_array' => $this->getRoleList(),
                    'role_admin_array' => $this->getRoleList("nom", 1),
                    'role_types_array' => $this->getRoleList("type"),
                    'fonction_array' => $this->getFonctionList(),
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->checkAutorisation('user_gerer');
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getLangList() {
        $dataProvider = Langue::find();
        return ArrayHelper::map($dataProvider->asArray()->all(), 'id', 'nom');
    }

    protected function getRoleList($param = "nom", $admin = 0) {
        if ($param == "type") {
            $dataProvider = Role::find()->allRolesTypes();
            return ArrayHelper::map($dataProvider->asArray()->all(), 'type', "type");
        } elseif ($admin == 1) {
            $dataProvider = Role::find()->adminRoles();
            return ArrayHelper::map($dataProvider->asArray()->all(), 'id', "nom");
        } else {
            $dataProvider = Role::find()->userRoles();
            return ArrayHelper::map($dataProvider->asArray()->all(), 'id', "nom");
        }
    }

    protected function getFonctionList() {
        $fonction = new Fonction();
        $fonction->setAttribute('id', -1);
        $fonction->setAttribute('nom', Yii::t('app', 'autre'));

        $dataProvider = Fonction::find()->asArray()->all();
        $dataProvider[] = $fonction;

        return ArrayHelper::map($dataProvider, 'id', 'nom');
    }

    function validateFonctionField($post, $model) {
        $fonction = new Fonction();
        $id = $post['User']['fonction'];
        $nf = $post['new_fonction'];

        // Nouvelle fonction
        if ($id == -1) {

            //new fonction vide ?
            if (empty($nf)) {
                return 0;
            }

            //fonction existante ?
            $query = Fonction::find()->where(['nom' => $nf])->one();
            if ($query) {
                $fonction->id = $query['id'];
                $fonction->nom = $query['nom'];
            } else {
                $fonction->nom = $nf;
                $fonction->save();
                //var_dump($fonction);
                //Yii::$app->end();
            }
            $model->fonction = $fonction->id;
        } else {
            $fonction = Fonction::find()->where(['id' => $id])->one();
            $model->fonction = $fonction->id;
        }
        //var_dump($model);
        //Yii::$app->end();
        return 1;
    }
    
    /*
     * Switch users
     */
    public function actionSwitch($id)
    {
        if ($id) {
            Yii::$app->user->identity->switchWith($id);
        }
        
        return $this->goHome();
    }
    
    
    
    
    


}
