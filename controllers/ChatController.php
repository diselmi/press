<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Message;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;

use app\models\Document;
use \yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * ChatController implements the CRUD actions for Message model.
 */
class ChatController extends Controller
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
     * Lists all Messages admin.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));
        }
        
        
        $this->layout = "layout_admin";
        $cUser = Yii::$app->user->identity;
        $users = User::find()->onlyAdmins()->all();
        //$users = ArrayHelper::getColumn($users, "id");
        
        $vav = null;
        
        //var_dump($users);
        //Yii::$app->end();
        
        
        //var_dump($users);
        //Yii::$app->end();
        if ($cUser->role0->nom == "client" || $cUser->role0->type == "client") {
            $this->layout = "layout_client";
            $users = User::getTeamOf($cUser->id);
            $vav = $cUser->superieur0->abonnement0->vis_a_vis0; 
        }
        return $this->render('index', [
            'users' => $users,
            'vav'   => $vav,
        ]);
    }
    
    /**
     * Lists all Messages client
     * @return mixed
     */
    public function actionCIndex()
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException(Yii::t('app', 'forbidden'));
        }
        $this->layout = "layout_admin";
        $cUser = Yii::$app->user->identity;
        $users = [];
        //$users = ArrayHelper::getColumn($users, "id");
        
        
        //var_dump($users);
        //Yii::$app->end();
        if ($cUser->role0->nom == "client" || $cUser->role0->type == "client") {
            $this->layout = "layout_client";
            $users = User::getTeamOf($cUser->id);
            $vav = $cUser->superieur0->abonnement0->vis_a_vis0;
            
            
        }
        return $this->render('index', [
            'users' => $users,
            'vav'   => $vav,
        ]);
    }

    public function actionConversation() {
        $reponse = [
            "status" => 0,
            "error"  => "",
            "user" => null,
            "cUser" => null,
            "messages" => array(),
        ];
        if (!Yii::$app->user->isGuest && Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post("id");
            $cUser = Yii::$app->user->identity;
            $user = User::findOne($id);
            if ($user) {
                Yii::$app->session['tmp_chat_user'] = $user;
                $messages = Message::find()->conversation($cUser->id, $user->id);
                $userJson = [
                    "id"        => $user->id,
                    "prenom"    => $user->prenom,
                    "nom"       => $user->nom,
                    "photo"     => $user->photo,
                    "role"      => $user->role0->nom,
                    "chemin"    => "/uploads/".md5($user->mail)."/message-files/",
                ];
                $cUserJson = [
                    "id"        => $cUser->id,
                    "prenom"    => $cUser->prenom,
                    "nom"       => $cUser->nom,
                    "photo"     => $cUser->photo,
                    "chemin"    => "/uploads/".md5($cUser->mail)."/message-files/"
                ];
                $reponse['status'] = 1;
                $reponse['user'] = $userJson;
                $reponse['cUser'] = $cUserJson;
                $reponse['messages'] = $messages;
                
            }
        
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $reponse;
        }
        
    }

    /**
     * Creates a new Message model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSend()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = new Message();
            //var_dump($_FILES);
            //var_dump($_POST);
            //Yii::$app->end();
        
        $reponse = [
            "status" => 0,
            "error"  => "",
            "message" => "",
            "file" => "",
        ];
            
        if (!Yii::$app->user->isGuest && Yii::$app->request->isPost && isset($_FILES["chat-file"])) {
            //$file = $_POST["chat-file"];
            if (!isset(Yii::$app->session['tmp_chat_user'])) {
                return;
            }
            $chemin = "uploads/".md5(Yii::$app->user->identity->mail)."/message-files/";
            if (!is_dir($chemin)) {
                \yii\helpers\FileHelper::createDirectory($chemin, 0777);
            }

            $doc = new Document();
            $model->expediteur = Yii::$app->user->id;
            $model->destinataire = Yii::$app->session['tmp_chat_user']['id'];
            $model->texte = ".";
            $doc->fichier = UploadedFile::getInstanceByName("chat-file");
            //$doc->fichier = $_FILES["chat-file"];
            $doc->gallery = null;
            if (!(  $doc->upload($chemin)  )) {
                $reponse["error"] = Yii::t("app", "le fichier ne peut pas être chargé au serveur!");
                return $reponse;
            }else {
                $model->document = $doc->id;
            }
        }
            
        if (!Yii::$app->user->isGuest && Yii::$app->request->isPost && isset($_POST["chat-text"])&& isset($_POST["destinataire"])) {
       
            $text = $_POST["chat-text"];
            $destinataire = $_POST["destinataire"];
            $model->expediteur = Yii::$app->user->id;
            $model->destinataire = $destinataire;
            $model->texte = $text;
            //var_dump(new \Datetime("now"));
            //Yii::$app->end();
            //$model->date_envoie = date("now");
            
        }
        
        if ($model->save()) {
            $reponse["status"] = 1;
            $reponse["message"] = $model->texte;

        }else {
            var_dump($model->errors);
            var_dump(Yii::$app->session['tmp_chat_user']);
        }
        
        return $reponse;
    }

    /**
     * Finds the Message model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Message the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Message::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
