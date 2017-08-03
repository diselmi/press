<?php

namespace app\models;

use Yii;
/**
 * This is the ActiveQuery class for [[Message]].
 *
 * @see Message
 */
class MessageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Message[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Message|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    public function conversation($id1, $id2) 
    {
        $liste = Message::find()->where( ['or',
            ['expediteur'=>$id1, 'destinataire'=>$id2],
            ['expediteur'=>$id2, 'destinataire'=>$id1]
            ])->orderBy('date_envoie ASC')->all();
        //var_dump($liste);
        //Yii::$app->end();
        foreach ($liste as &$message) {
            if ($message['destinataire'] == Yii::$app->user->id && $message['vu'] == 0) {
                $message['vu'] = 1;
            }
            $message->save();
            $message['date_envoie'] = date("d-m-Y H:i", strtotime($message['date_envoie']));
            if ($message['document']) {
                $message['document'] = $message->document0->chemin;
            }
            
        }
        
        return $liste;
    }
    
    public function messagesNonLus() {
        $cUser = Yii::$app->user;
        $liste = Message::find()->where(['destinataire'=>$cUser->id, 'vu'=>0])->orderBy('date_envoie DESC')->all();
        //var_dump($liste);
        //Yii::$app->end();
        foreach ($liste as &$message) {
            $message['date_envoie'] = date("d-m-Y H:i", strtotime($message['date_envoie']));
            if ($message['document']) {
                $message['document'] = $message->document0->chemin;
            }
            
        }
        
        return $liste;
        
    }
    
    
}
