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
        if ($id && $this->findModel($id)->role0->nom == "superadmin") {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden')); 
        }
        if ($cUser && $id && ($this->findModel($id)->cree_par != $cUser->id) ){
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden')); 
        }elseif (!$cUser || !$cUser->role0->attributes[$permission]) {
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
        $abonnement = new Abonnement();
        $abonnement->date_debut = date('d-m-Y');
        $abonnement->date_fin = date('d-m-Y');

        $model->imagePhoto = UploadedFile::getInstance($model, 'photo');
        $model->imageLogo = UploadedFile::getInstance($model, 'logo');

        if ($model->load(Yii::$app->request->post()) && $abonnement->load(Yii::$app->request->post()) ) {
            $model->login = $model->mail;
            $model->pass = bin2hex(random_bytes(5));
            $abonnement->date_debut = date_format(new \Datetime($abonnement->date_debut), "Y-m-d");
            $abonnement->date_fin = date_format(new \Datetime($abonnement->date_fin), "Y-m-d");
            //var_dump($abonnement);
            //Yii::$app->end();
            
            
            if ($this->validateFonctionField(Yii::$app->request->post(), $model)) {
                
                //$abonnement->link("client0", $model);
                $model->cree_par = Yii::$app->user->id;
                $abonnement->vis_a_vis = Yii::$app->user->id;
                $abonnement->etat = 1;
                
                $db = Yii::$app->db; $transaction = $db->beginTransaction();
                
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

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $this->checkAutorisation('user_gerer', $id);
        
        $model = $this->findModel($id);
//        $model->abonnement = new Abonnement();
//        if ($model->abonnement0 === null) {
//            $model->abonnement = new Abonnement();
//        }

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
//                $model->link("abonnement0", $model->abonnement);
//                var_dump(Yii::$app->request->post());
//                Yii::$app->end();
                //var_dump($model);
                //Yii::$app->end();
                if (!$model->cree_par) {
                    $model->cree_par = Yii::$app->user->id;
                }
                if ($model->save() && $model->upload()) {
                    //var_dump(Yii::$app->user->identity->role0);
                    //Yii::$app->end();
                    
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

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->checkAutorisation('user_gerer', $id);
        
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
