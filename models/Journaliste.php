<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "journaliste".
 *
 * @property integer $id
 * @property string $nom
 * @property string $mail
 * @property string $numtel
 * @property string $photo
 * @property string $siteweb
 * @property string $facebook
 * @property string $twitter
 * @property string theme
 *
 * @property JournalisteMedia[] $journalisteMedia
 * @property Media[] $medias
 */
class Journaliste extends \yii\db\ActiveRecord
{
    
    /**
     * @var UploadedFile
     */
    public $fichier_photo;
    
    public static $themes = ["theme1", "theme2", "theme3"];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'journaliste';
    }
    
    public static function allThemes()
    {
        return array_combine(Journaliste::$themes, Journaliste::$themes);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nom', 'mail', 'numtel'], 'required'],
            [['nom', 'mail', 'numtel', 'theme'], 'string', 'max' => 64],
            [['siteweb', 'facebook', 'twitter'], 'string', 'max' => 256],
            ['photo', 'safe'],
            [['fichier_photo'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif'],
            [['facebook', 'siteweb', 'twitter'], 'url'],
            ['facebook', 'validateFacebook'],
            ['twitter', 'validateTwitter'],
            ['numtel', 'validatePhone'],
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
            'mail' => Yii::t('app', 'Mail'),
            'numtel' => Yii::t('app', 'Numtel'),
            'fichier_photo' => Yii::t('app', 'Photo'),
            'siteweb' => Yii::t('app', 'Siteweb'),
            'facebook' => Yii::t('app', 'Facebook'),
            'twitter' => Yii::t('app', 'Twitter'),
            'theme' => Yii::t('app', 'Theme'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJournalisteMedia()
    {
        return $this->hasMany(JournalisteMedia::className(), ['journaliste' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedias()
    {
        return $this->hasMany(Media::className(), ['id' => 'media'])->viaTable('journaliste_media', ['journaliste' => 'id']);
    }

    /**
     * @inheritdoc
     * @return JournalisteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new JournalisteQuery(get_called_class());
    } 
    
    
    public function upload()
    {   
        $chemin = "uploads/Journalistes/".md5("journaliste".$this->id)."/";

        if ( ! is_dir($chemin)) {
            mkdir($chemin, 0777, true);
        }
        
        if ($this->fichier_photo !==null) {
            $chemin_photo = $chemin."logo.".$this->fichier_photo->extension;
            $this->fichier_photo->saveAs($chemin_photo);
            $this->photo = "/".$chemin_photo;

            $this->offsetUnset('fichier_photo');
            
        }
        if ($this->validate()) {
            $this->save();
            return true;
        }
            
        
        return false;
    }
    
    
    
    
}
