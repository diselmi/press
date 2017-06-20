<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $rc; // raison sociale
    public $nom;
    public $prenom;
    public $numtel;
    public $email;
    public $adresse;
    public $objet;
    public $contenu;
    public $verifyCode;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['nom', 'prenom', 'email', 'objet', 'contenu'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'rc' => Yii::t('app', 'Raison sociale'),
            'nom' => Yii::t('app', 'Nom'),
            'prenom' => Yii::t('app', 'Prenom'),
            'email' => Yii::t('app', 'Email'),
            'numtel' => Yii::t('app', 'Telephone'),
            'objet' => Yii::t('app', 'Objet'),
            'contenu' => Yii::t('app', 'Contenu'),
            'verifyCode' => 'Code de verification',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            /*Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([$this->email => $this->prenom." ".$this->nom])
                ->setSubject($this->objet)
                ->setTextBody($this->contenu)
                ->send();*/
            $contenu_rc = $this->rc ? "Raison sociale: ". $this->rc . "\n" : "";
            $mailbody = "<h3>Formulaire de contact:</h3> <br>"
                . $contenu_rc
                . "<b>Nom:</b> " . $this->prenom." ".$this->nom . "<br>"
                . "<b>Email:</b> " . $this->email . "<br>"
                . "<b>Objet:</b> " . $this->objet . "<br>"
                . "<b>Contenu:</b><br>" . $this->contenu;
            
             try {
                Yii::$app->mailer->compose('layouts/html', [
                        'content' => $mailbody
                    ])
                    ->setFrom($email)
                    ->setTo('selmi.aladdin@gmail.com')
                    ->setReplyTo([$this->email => $this->prenom." ".$this->nom." (".$this->rc.")"])
                    ->setSubject($this->objet)
                    //->setHtmlBody($mailbody)
                    ->send();
             } catch (Swift_TransportException $ex) {
                 
             }
            

            return true;
        }
        return false;
    }
}
