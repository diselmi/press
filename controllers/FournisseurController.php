<?php

namespace app\controllers;

use Yii;
use app\models\Fournisseur;
use app\models\FournisseurSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\filters\VerbFilter;

use app\models\Gallery;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

use yii\helpers\ArrayHelper;
use app\models\User;

/**
 * FournisseurController implements the CRUD actions for Fournisseur model.
 */
class FournisseurController extends Controller
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
     * Lists all Fournisseur models.
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
        
        $searchModel = new FournisseurSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Fournisseur model.
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
        $model = $this->findModel($id);
        $ch_f = "/uploads/fournisseurs/".$model->dossier;
        $liste_photos = $model->gallery_photos ? Gallery::find()->DocsByGallery($model->gallery_photos, $ch_f."/photos") : null;
        $liste_pdf = $model->gallery_pdf ? Gallery::find()->DocsByGallery($model->gallery_pdf, $ch_f."/docs") : null;
        
        
        return $this->render('view', [
            'model' => $model,
            'liste_photos'  => $liste_photos,
            'liste_pdf'     => $liste_pdf,
        ]);
    }

    /**
     * Creates a new Fournisseur model.
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
        $model = new Fournisseur();
        
        $gallery_photos = new Gallery();
        $gallery_pdf = new Gallery();
        
        $gallery_photos->fichiers = UploadedFile::getInstancesByName('photos');
        $gallery_pdf->fichiers = UploadedFile::getInstancesByName('documents');

        if ($model->load(Yii::$app->request->post())) {
            
            //var_dump($gallery_photos->fichiers);
            //Yii::$app->end();
            $model->dossier = substr(md5(date("yyyy m d h:i:s")), 0, 15);
            $model->cree_par = Yii::$app->user->id;
            //$f = substr(md5(date("yyyy m d h:i:s")), 0, 15);
            $chemin_photos = "/uploads/fournisseurs/".$model->dossier."/photos/";
            $chemin_pdf = "/uploads/fournisseurs/".$model->dossier."/docs/";
            
            $db = Yii::$app->db; $transaction = $db->beginTransaction();  
            if ( $gallery_photos->fichiers && !( $gallery_photos->save() && $gallery_photos->upload($chemin_photos, ["image", "video"] )  )) {
                throw new Exception("Cannot upload 'Gallery de photos' \n". implode(", ", array_values($gallery_photos->errors)[0]) );
            }elseif ( $gallery_pdf->fichiers && !( $gallery_pdf->save() && $gallery_pdf->upload($chemin_pdf, ["pdf"])  )) {
                throw new Exception("Cannot upload 'Gallery de pdf' \n". implode(", ", array_values($gallery_pdf->errors)[0]) );
            }else{
                $model->gallery_photos = $gallery_photos->id;
                $model->gallery_pdf = $gallery_pdf->id;
                
                if (! ($model->save() && $model->uploadLogo())) {
                    $transaction->rollBack();
                    throw new Exception("Cannot save Fournisseur \n". implode(", ", array_values($model->errors)[0]) );
                }else {
                    $transaction->commit();
                }  
            }
            
            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Fournisseur model.
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
        

        if ($model->load(Yii::$app->request->post())) {
            
            $gallery_photos = Gallery::findOne($model->galleryPhotos);
            $gallery_pdf = Gallery::findOne($model->galleryPdf);
            if (!$gallery_photos) {
                $gallery_photos = new Gallery();
            }
            if (!$gallery_pdf) {
                $gallery_pdf = new Gallery();
            }
            
            $model->fichier_logo = UploadedFile::getInstance($model, 'logo');
            $gallery_photos->fichiers = UploadedFile::getInstancesByName('photos');
            $gallery_pdf->fichiers = UploadedFile::getInstancesByName('documents');
            
            //var_dump($gallery_photos->fichiers);
            //Yii::$app->end();
            
            $chemin_photos = "uploads/fournisseurs/".$model->dossier."/photos/";
            $chemin_pdf = "uploads/fournisseurs/".$model->dossier."/docs/";
            
            $db = Yii::$app->db; $transaction = $db->beginTransaction();  
            if ( $gallery_photos->fichiers && !( $gallery_photos->save() && $gallery_photos->upload($chemin_photos, ["image", "video"] )  )) {
                throw new Exception("cannot_save_gallery_photos \n". implode(", ", array_values($gallery_photos->errors)[0]) );
            }elseif ( $gallery_pdf->fichiers && !( $gallery_pdf->save() && $gallery_pdf->upload($chemin_pdf, ["pdf"])  )) {
                throw new Exception("cannot_save_gallery_pdf \n". implode(", ", array_values($gallery_pdf->errors)[0]) );
            }else{
                $model->gallery_photos = $gallery_photos->id;
                $model->gallery_pdf = $gallery_pdf->id;
                if (! ($model->save() && $model->uploadLogo())) {
                    $transaction->rollBack();
                    throw new Exception("cannot_save_fournisseur \n". implode(", ", array_values($model->errors)[0]) );
                }else {
                    $transaction->commit();
                }  
            }
            
            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Fournisseur model.
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
        
        if ($model) {
            if ($model->galleryPhotos) {
                $gallery_photos = Gallery::find(['id' => $model->galleryPhotos->id])->one();
                $gallery_photos->delete();
            }
            
            if ($model->galleryPdf) {
                $gallery_pdf = Gallery::find(['id' => $model->galleryPdf->id])->one();
                $gallery_pdf->delete();
            }
            
            $model->delete();
        } 

        return $this->redirect(['index']);
    }

    /**
     * Finds the Fournisseur model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fournisseur the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fournisseur::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
