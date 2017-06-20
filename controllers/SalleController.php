<?php

namespace app\controllers;

use Yii;
use app\models\Salle;
use app\models\SalleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\filters\VerbFilter;

use app\models\Gallery;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * SalleController implements the CRUD actions for Salle model.
 */
class SalleController extends Controller
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
        if ($cUser && $id && ($this->findModel($id)->cree_par != $cUser->id && $this->findModel($id)->cree_par != $cUser->superieur) ){
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden')); 
        }elseif (!$cUser || !$cUser->role0->attributes[$permission]) {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden')); 
        }
    }

    /**
     * Lists all Salle models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->checkAutorisation('salle_gerer');
        $searchModel = new SalleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Salle model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        $ch_f = "/uploads/salles/".$model->dossier;
        $liste_photos = $model->gallery_photos ? Gallery::find()->DocsByGallery($model->gallery_photos, $ch_f."/photos") : null;
        $liste_pdf = $model->gallery_pdf ? Gallery::find()->DocsByGallery($model->gallery_pdf, $ch_f."/docs") : null;
        
        
        return $this->render('view', [
            'model' => $model,
            'liste_photos'  => $liste_photos,
            'liste_pdf'     => $liste_pdf,
        ]);
    }

    /**
     * Creates a new Salle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->checkAutorisation('salle_gerer');
        $model = new Salle();
        
        $gallery_photos = new Gallery();
        $gallery_pdf = new Gallery();
        
        if (!$gallery_photos) {
            $gallery_photos = new Gallery();
        }
        if (!$gallery_pdf) {
            $gallery_pdf = new Gallery();
        }
        

        if ($model->load(Yii::$app->request->post())) {
            $model->dossier = substr(md5(date("yyyy m d h:i:s")), 0, 15);
            
            $model->fichier_image = UploadedFile::getInstance($model, 'image');
            $gallery_photos->fichiers = UploadedFile::getInstancesByName('photos');
            $gallery_pdf->fichiers = UploadedFile::getInstancesByName('documents');
            
            $chemin_photos = "uploads/salles/".$model->dossier."/photos/";
            $chemin_pdf = "uploads/salles/".$model->dossier."/docs/";
            
            $db = Yii::$app->db; $transaction = $db->beginTransaction();  
            if ( $gallery_photos->fichiers && !( $gallery_photos->save() && $gallery_photos->upload($chemin_photos, ["image", "video"] )  )) {
                throw new Exception("cannot_save_gallery_photos \n". implode(", ", array_values($gallery_photos->errors)[0]) );
            }elseif ( $gallery_pdf->fichiers && !( $gallery_pdf->save() && $gallery_pdf->upload($chemin_pdf, ["pdf"])  )) {
                throw new Exception("cannot_save_gallery_pdf \n". implode(", ", array_values($gallery_pdf->errors)[0]) );
            }else{
                $model->gallery_photos = $gallery_photos->id;
                $model->gallery_pdf = $gallery_pdf->id;
                if (! ($model->save() && $model->uploadImage())) {
                    $transaction->rollBack();
                    throw new Exception("cannot_save_salle \n". implode(", ", array_values($model->errors)[0]) );
                }else {
                    $transaction->commit();
                }  
            }
            
            $model->cree_par = Yii::$app->user->id;
            
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        }
        return $this->render('create', [
            'model' => $model,
        ]);
        
    }

    /**
     * Updates an existing Salle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->checkAutorisation('salle_gerer', $id);
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            
            $gallery_photos = Gallery::findOne($model->galleryPhotos);
            $gallery_pdf = Gallery::findOne($model->galleryPdf);
            
            if (!$gallery_photos) {
                $gallery_photos = new Gallery();
            }
            if (!$gallery_pdf) {
                $gallery_pdf = new Gallery();
            }
            
            $model->fichier_image = UploadedFile::getInstance($model, 'image');
            $gallery_photos->fichiers = UploadedFile::getInstancesByName('photos');
            $gallery_pdf->fichiers = UploadedFile::getInstancesByName('documents');
            
            //var_dump($gallery_photos->fichiers);
            //Yii::$app->end();
            
            
            $chemin_photos = "uploads/salles/".$model->dossier."/photos/";
            $chemin_pdf = "uploads/salles/".$model->dossier."/docs/";
            
            $db = Yii::$app->db; $transaction = $db->beginTransaction();  
            if ( $gallery_photos->fichiers && !( $gallery_photos->save() && $gallery_photos->upload($chemin_photos, ["image", "video"] )  )) {
                throw new Exception("cannot_save_gallery_photos \n". implode(", ", array_values($gallery_photos->errors)[0]) );
            }elseif ( $gallery_pdf->fichiers && !( $gallery_pdf->save() && $gallery_pdf->upload($chemin_pdf, ["pdf"])  )) {
                throw new Exception("cannot_save_gallery_pdf \n". implode(", ", array_values($gallery_pdf->errors)[0]) );
            }else{
                $model->gallery_photos = $gallery_photos->id;
                $model->gallery_pdf = $gallery_pdf->id;
                if (! ($model->save() && $model->uploadImage())) {
                    $transaction->rollBack();
                    throw new Exception("cannot_save_salle \n". implode(", ", array_values($model->errors)[0]) );
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
     * Deletes an existing Salle model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->checkAutorisation('salle_gerer', $id);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Salle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Salle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Salle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
