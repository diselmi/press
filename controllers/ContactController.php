<?php

namespace app\controllers;

use Yii;
use app\models\Contact;
use app\models\ContactSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContactController implements the CRUD actions for Contact model.
 */
class ContactController extends Controller
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

    /**
     * Lists all Contact models.
     * @return mixed
     */
    public function actionIndexContacts()
    {
        $searchModel = new ContactSearch(2);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index_contact', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all Contact models.
     * @return mixed
     */
    public function actionIndexDemandes()
    {
        $searchModel = new ContactSearch(3);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index_demande', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contact model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->consulte = true; $model->save(false);
        $page = "view_contact";
        if ($model && $model->type == 3) $page = "view_demande";
        
        if (Yii::$app->request->isPost) {
            $form = Yii::$app->request->post();
            //var_dump(Yii::$app->request->post());
            //Yii::$app->end();
            if (isset($form['reponse'])) {
                $this->repondre($model->email, $model->objet, $form['reponse']);
            }
        }
        
        
        return $this->render($page, [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Deletes an existing Contact model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    
    /**
     * Finds the Contact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    private function repondre($email, $obj, $msg)
    {
        Yii::$app->mailer->compose()
        ->setFrom(Yii::$app->params['adminEmail'])
        ->setTo($email)
        //->setReplyTo([$this->email => $this->prenom." ".$this->nom." (".$this->rc.")"])
        ->setSubject($obj)
        ->setTextBody($msg)
        ->send();
        //var_dump(Yii::$app->mailer);
        //Yii::$app->end();
    }
}
