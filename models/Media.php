<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "media".
 *
 * @property integer $id
 * @property string $nom
 * @property string $adresse
 * @property string $mail
 * @property string $numtel
 * @property string $logo
 * @property boolean $tv
 * @property boolean $radio
 * @property boolean $j_papier
 * @property boolean $j_electronique
 * @property string $siteweb
 * @property string $facebook
 * @property string $twitter
 * @property string $date_creation
 * @property integer $pr_value
 * @property integer $cree_par
 * 
 * @property User $creePar
 * @property JournalisteMedia[] $journalisteMedia
 * @property Journaliste[] $journalistes
 */
class Media extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $fichier_logo;
    
    public $types;
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nom', 'mail'], 'required'],
            [['tv', 'radio', 'j_papier', 'j_electronique'],'boolean'],
            [['adresse'], 'string'],
            [['date_creation'], 'safe'],
            [['nom'], 'string', 'max' => 64],
            ['mail', 'email'],
            [['facebook', 'siteweb', 'twitter'], 'url'],
            ['facebook', 'validateFacebook'],
            ['twitter', 'validateTwitter'],
            ['numtel', 'validatePhone'],
            ['pr_value', 'integer'],
            [['fichier_logo'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif'],
            [['cree_par'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['cree_par' => 'id']],
            
        ];
    }
    
    public function validatePhone($attribute, $params, $validator)
    {
        if (!is_numeric($this->$attribute)) {
            $this->addError($attribute, Yii::t("app", "Numero de telephone non valide"));
        }
    }
    public function validateFacebook($attribute, $params, $validator)
    {
        if (strpos($this->$attribute, "facebook.com") == null) {
            $this->addError($attribute, Yii::t("app", "Compte Facebook non valide"));
        }
    }
    public function validateTwitter($attribute, $params, $validator)
    {
        if (strpos($this->$attribute, "twitter.com") == null) {
            $this->addError($attribute, Yii::t("app", "Compte Twitter non valide"));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nom' => Yii::t('app', 'Nom'),
            'adresse' => Yii::t('app', 'Adresse'),
            'mail' => Yii::t('app', 'Email'),
            'numtel' => Yii::t('app', 'Numtel'),
            'fichier_logo' => Yii::t('app', 'Logo'),
            'siteweb' => Yii::t('app', 'Siteweb'),
            'facebook' => Yii::t('app', 'Facebook'),
            'twitter' => Yii::t('app', 'Twitter'),
            'date_creation' => Yii::t('app', 'Date Creation'),
            'cree_par' => Yii::t('app', 'Cree Par'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreePar()
    {
        return $this->hasOne(User::className(), ['id' => 'cree_par']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJournalisteMedia()
    {
        return $this->hasMany(JournalisteMedia::className(), ['media' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJournalistes()
    {
        return $this->hasMany(Journaliste::className(), ['id' => 'journaliste'])->viaTable('journaliste_media', ['media' => 'id']);
    }

    /**
     * @inheritdoc
     * @return MediaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MediaQuery(get_called_class());
    }
    
    public function getTypes()
    {
        return $types = [
            "tv"                => $this->tv==1,
            "radio"             => $this->radio==1,
            "j_papier"          => $this->j_papier==1,
            "j_electronique"    => $this->j_electronique==1 ];
    }
    
    public static function allTypes()
    {
        $liste_types = [
            "tv" => Yii::t("app", "tv"),
            "radio" => Yii::t("app", "radio"),
            "j_papier" => Yii::t("app", "j_papier"),
            "j_electronique" => Yii::t("app", "j_electronique"),
        ];
        return $liste_types;
    }
    
    
    public function upload()
    {   
        $chemin = "uploads/Media/".md5("media".$this->id)."/";

        if ( ! is_dir($chemin)) {
            mkdir($chemin, 0777, true);
        }
        
        if ($this->fichier_logo !==null) {
            $chemin_logo = $chemin."logo.".$this->fichier_logo->extension;
            $this->fichier_logo->saveAs($chemin_logo);
            $this->logo = "/".$chemin_logo;

            $this->offsetUnset('fichier_logo');
            
        }
        if ($this->validate()) {
            $this->save();
            return true;
        }
            
        
        return false;
    }
    
    
}
