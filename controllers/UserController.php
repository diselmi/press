<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\models\Langue;
use app\models\Role;
use app\models\Fonction;
use app\models\Abonnement;
use yii\web\UploadedFile;

use yii\helpers\Url;
/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller {

    public $layout = "layout_admin";
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
        if ($cUser && ($cUser->role0->nom == "client" || $cUser->role0->type == "client")) {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));
        }
        if ($id && $this->findModel($id)->role0->nom == "superadmin") {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));  
        }elseif (!$cUser || !$cUser->role0->attributes[$permission]) {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden')); 
        }
    }
    
    public function checkClientAutorisation($permission)
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
     * Lists all User models.
     * @return mixed
     */
    public function actionCIndex() {
        
        $this->checkClientAutorisation('user_gerer');
        $this->layout = "layout_client";
        $cUser = Yii::$app->user->identity;
        //$team = User::getTeamOf($cUser->id);
        
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->cSearch(Yii::$app->request->queryParams, $cUser->id);

        return $this->render('c_index', [
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
        
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        if ($model->role0->nom == 'client') {
            $dataProvider = $searchModel->cSearch(Yii::$app->request->queryParams, $id);
            return $this->render('view', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }elseif ($model->role0->type == 'client') {
            $dataProvider = $searchModel->cSearch(Yii::$app->request->queryParams, $id);
            return $this->render('view_emp', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }else {
            return $this->render('view_admin', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }   
    }
    
    public function actionCView($id) {
        $this->checkClientAutorisation('user_gerer');
        $this->layout = "layout_client";
        $model = $this->findModel($id);
        if ($model->role0->type == "admin") {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));
        }
        $model->logo = $model->superieur0->logo;
        return $this->render('c_view', [
            'model' => $model,
        ]);
        
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $this->checkAutorisation('user_gerer');
        $model = new User();
        $abonnement = new Abonnement();
        $abonnement->date_debut = date('d-m-Y');
        $abonnement->date_fin = date('d-m-Y');

        $model->imagePhoto = UploadedFile::getInstance($model, 'photo');
        $model->imageLogo = UploadedFile::getInstance($model, 'logo');

        if ($model->load(Yii::$app->request->post()) && $abonnement->load(Yii::$app->request->post()) ) {
            $model->login = $model->mail;
            $model->pass = bin2hex(openssl_random_pseudo_bytes(5));
            
            $abonnement->date_debut = date_format(new \Datetime($abonnement->date_debut), "Y-m-d");
            $abonnement->date_fin = date_format(new \Datetime($abonnement->date_fin), "Y-m-d");
            //var_dump($abonnement);
            //Yii::$app->end();
            
            
            if ($this->validateFonctionField(Yii::$app->request->post(), $model)) {
                
                //$abonnement->link("client0", $model);
                $model->cree_par = Yii::$app->user->id;
                $abonnement->vis_a_vis = Yii::$app->user->id;
                $abonnement->etat = 'p';
                
                $db = Yii::$app->db; $transaction = $db->beginTransaction();
                //var_dump($abonnement);
                //Yii::$app->end();
                
                if (! $model->save()) {
                    throw new Exception("Cannot save the User \n". implode(", ", array_values($model->errors)[0]) );
                }else{
                    $abonnement->client = $model->id;
                    
                    if (!$abonnement->save()) {
                        $transaction->rollBack();
                        throw new Exception("Cannot save the Abonnement \n". implode(", ", array_values($abonnement->errors)[0]) );
                    }else {
                        $transaction->commit();
                        
                    }  
                }
                
                
                
                if ($model->upload()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }else {
                    //var_dump($model->errors);
                    //var_dump($model->abonnement->attributes);
                    //Yii::$app->end();
                }
            }
        }else {
            //var_dump($model->load(Yii::$app->request->post()));
            //Yii::$app->end();
        }

        return $this->render('create', [
                    'model' => $model,
                    'abonnement' => $abonnement,
                    'lang_array' => $this->getLangList(),
                    'role_array' => $this->getRoleList(),
                    'role_admin_array' => $this->getRoleList("nom", 1), // admin only
                    'fonction_array' => $this->getFonctionList(),
        ]);
    }
    
    public function actionCCreate() {
        $this->checkClientAutorisation('user_gerer');
        $this->layout = "layout_client";
        $model = new User();
        $model->lang = Yii::$app->user->identity->lang;
        
        $model->imagePhoto = UploadedFile::getInstance($model, 'photo');
        
        if ($model->load(Yii::$app->request->post())) {
            $model->login = $model->mail;
            $model->pass = bin2hex(openssl_random_pseudo_bytes(5));
            $model->superieur = Yii::$app->user->identity->superieur0->id;
            $model->couleur_interface = $model->superieur0->couleur_interface;
            $model->cree_par = Yii::$app->user->id;
            if ($this->validateFonctionField(Yii::$app->request->post(), $model)) {
                if (! $model->save()) {
                    //throw new Exception("Cannot save the User \n". implode(", ", array_values($model->errors)[0]) );
                }else{
                    if ($model->upload()) {
                        return $this->redirect(['c-view', 'id' => $model->id]);
                    }   
                }
            }
        }else {
            //var_dump($model->load(Yii::$app->request->post()));
            //Yii::$app->end();
        }

        return $this->render('c_create', [
                    'model' => $model,
                    'lang_array' => $this->getLangList(),
                    'role_client_array' => $this->getRoleList("nom", 0), // client only
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
        $this->checkAutorisation('user_gerer', $id);
        
        $model = $this->findModel($id);
        $model->role_type = $model->role0->type;

        $model->imagePhoto = UploadedFile::getInstance($model, 'photo');
        $model->imageLogo = UploadedFile::getInstance($model, 'logo');

        if ($model->load(Yii::$app->request->post())) {

            // verifier Couleur interface
            if ($model->couleur_interface && (strlen($model->couleur_interface) != 7) && !ctype_xdigit($model->couleur_interface)) {
                $model->couleur_interface = "ccc";
            }

            // Valider champ Fonction
            if ($this->validateFonctionField(Yii::$app->request->post(), $model)) {
                if (!$model->cree_par) {
                    $model->cree_par = Yii::$app->user->id;
                }
                if ($model->save() && $model->upload()) {
                    return $this->redirect(['view', 'id' => $model->id]); 
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
    
    public function actionCUpdate($id) {
        $this->checkClientAutorisation('user_gerer');
        $this->layout = "layout_client";
        
        $model = $this->findModel($id);
        if ($model->role0->type == "admin") {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));
        }
        $model->role_type = $model->role0->type;

        $model->imagePhoto = UploadedFile::getInstance($model, 'photo');

        if ($model->load(Yii::$app->request->post())) {

            $model->superieur = Yii::$app->user->identity->superieur0->id;
            $model->couleur_interface = $model->superieur0->couleur_interface;
            
            // Valider champ Fonction
            if ($this->validateFonctionField(Yii::$app->request->post(), $model)) {
                if (!$model->cree_par) {
                    $model->cree_par = Yii::$app->user->id;
                }
                if ($model->save() && $model->upload()) {
                    return $this->redirect(['c-view', 'id' => $model->id]); 
                }
            }
        }

        return $this->render('c_update', [
                    'model' => $model,
                    'lang_array' => $this->getLangList(),
                    'role_client_array' => $this->getRoleList("nom", 0),
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
        $this->checkAutorisation('user_gerer', $id);
        
        $model = $this->findModel($id);
        if ($model->role0->type == "admin") {
            $chemin = "/uploads/".md5($model->mail);
            \yii\helpers\FileHelper::removeDirectory($chemin);
            $model->delete();
        }
        
        

        return $this->redirect(['index']);
    }
    
    public function actionCDelete($id) {
        $this->checkClientAutorisation('user_gerer', $id);
        
        $model = $this->findModel($id);
        if ($model->role0->type == "client") {
           $model->delete();
        }

        return $this->redirect(['c-index']);
    }
    
    public function actionProfile() {
        //$this->checkAutorisation('user_gerer', $id);
        $model = Yii::$app->user->identity;
        
        //$model = $this->findModel($id);

        $model->imagePhoto = UploadedFile::getInstance($model, 'photo');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save() && $model->upload()) {
                return $this->redirect(['site/dashboard', 'id' => $model->id]); 
            }
        }
        
        if ($model->role0->type == "client" || $model->role0->nom == "client") {
            $this->layout = "layout_client";
            return $this->render('_c_profile_edit', [
                        'model' => $model,
                        'lang_array' => $this->getLangList(),
                        'fonction_array' => $this->getFonctionList(0),
            ]);
        }else {
            $this->layout = "layout_admin";
            return $this->render('_profile_edit', [
                        'model' => $model,
                        'lang_array' => $this->getLangList(),
            ]);
        }

        
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

    protected function getFonctionList($autre = 1) {
        $fonction = new Fonction();
        $fonction->setAttribute('id', -1);
        $fonction->setAttribute('nom', Yii::t('app', 'autre'));

        $dataProvider = Fonction::find()->asArray()->all();
        if($autre) $dataProvider[] = $fonction ;

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
    public function actionSwitch($id) {
        //$this->checkAutorisation('user_gerer');
        if ($id) {
            Yii::$app->user->identity->switchWith($id);
        }
        
        //return $this->goHome();
        return $this->redirect(Url::toRoute("/dashboard"));
    }
    
    
    
    
    


}
