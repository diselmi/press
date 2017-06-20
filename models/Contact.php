<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact".
 *
 * @property integer $id
 * @property integer $type 1:newsletter 2:contact 3:register 
 * @property string $nom
 * @property string $prenom
 * @property string $numtel
 * @property string $email
 * @property string $adresse
 * @property string $rs
 * @property string $objet
 * @property string $contenu
 * @property string $apropos
 * @property string $metier
 * @property string $entendu_par
 * @property boolean $etre_notifie
 * @property boolean $etre_contacte
 * @property boolean $consulte
 */
class Contact extends \yii\db\ActiveRecord
{
    const SCENARIO_CONTACT = 'contact';
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_NEWSLETTER = 'newsletter';
    
    public $verifyCode;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact';
    }
    
    public function scenarios()
    {
        return [
            self::SCENARIO_NEWSLETTER => ['email', 'verifyCode'],
            self::SCENARIO_CONTACT => ['nom', 'prenom', 'rs', 'numtel', 'email', 'adresse', 'objet', 'contenu', 'verifyCode'],
            self::SCENARIO_REGISTER => ['nom', 'prenom', 'numtel', 'email', 'adresse', 'metier', 'apropos','etre_notifie', 'etre_contacte', 'entendu_par', 'verifyCode'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'email', 'verifyCode'], 'required'],
            [['nom', 'prenom', 'numtel', 'adresse', 'objet', 'contenu'], 'required', 'on' => self::SCENARIO_CONTACT],
            [['nom', 'prenom', 'numtel', 'adresse', 'metier', 'apropos', 'entendu_par'], 'required', 'on' => self::SCENARIO_REGISTER],
            [['type'], 'integer'],
            [['etre_notifie', 'etre_contacte'], 'boolean'],
            [['nom', 'prenom', 'numtel', 'email', 'rs', 'entendu_par', 'metier'], 'string', 'max' => 64],
            [['adresse', 'objet'], 'string', 'max' => 256],
            [['contenu', 'apropos'], 'string', 'max' => 1024],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Type'),
            'nom' => Yii::t('app', 'Nom'),
            'prenom' => Yii::t('app', 'Prenom'),
            'numtel' => Yii::t('app', 'Telephone'),
            'email' => Yii::t('app', 'Email'),
            'adresse' => Yii::t('app', 'Adresse'),
            'rs' => Yii::t('app', 'Raison sociale'),
            'objet' => Yii::t('app', 'Objet'),
            'contenu' => Yii::t('app', 'Contenu'),
            'apropos' => Yii::t('app', 'A propos'),
            'metier' => Yii::t('app', 'Metier'),
            'entendu_par' => Yii::t('app', 'Entendu Par'),
            'etre_notifie' => Yii::t('app', 'Etre Notifie'),
            'etre_contacte' => Yii::t('app', 'Etre Contacte'),
            'verifyCode' => Yii::t('app', 'Code de verification'),
        ];
    }
    
    public static function listeMetiers()
    {
        $liste = ['metier1', 'metier2', 'metier3', 'metier4', 'metier5'];
        return array_combine($liste, $liste);
    }
    
    public static function listeSources()
    {
        $liste = ['source1', 'source2', 'source3', 'source4', 'source5'];
        return array_combine($liste, $liste);
    }

    /**
     * @inheritdoc
     * @return ContactQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ContactQuery(get_called_class());
    }
}
