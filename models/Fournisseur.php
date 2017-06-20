<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

use yii\helpers\FileHelper;

/**
 * This is the model class for table "fournisseur".
 *
 * @property integer $id
 * @property string $nom
 * @property string $adresse
 * @property string $numtel
 * @property string $activite
 * @property integer $estPremium
 * @property string $logo
 * @property integer $gallery_photos
 * @property integer $gallery_pdf
 * @property string $siteweb
 * @property string $facebook
 * @property string $twitter
 * @property string $dossier
 *
 * @property Gallery $galleryPdf
 * @property Gallery $galleryPhotos
 */
class Fournisseur extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $fichier_logo;
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fournisseur';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nom', 'adresse', 'activite', 'estPremium'], 'required'],
            [['adresse'], 'string'],
            [['estPremium', 'gallery_photos', 'gallery_pdf'], 'integer'],
            [['nom'], 'string', 'max' => 64],
            [['facebook', 'siteweb', 'twitter'], 'url'],
            [['numtel', 'activite', 'dossier'], 'string', 'max' => 32],
            [['fichier_logo'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['gallery_pdf'], 'exist', 'skipOnError' => true, 'targetClass' => Gallery::className(), 'targetAttribute' => ['gallery_pdf' => 'id']],
            [['gallery_photos'], 'exist', 'skipOnError' => true, 'targetClass' => Gallery::className(), 'targetAttribute' => ['gallery_photos' => 'id']],
            [['numtel'], 'validatePhone'],
        ];
    }
    
    public function validatePhone($attribute, $params, $validator)
    {
        if (!is_numeric($this->$attribute)) {
            $this->addError($attribute, Yii::t("app", "Numero de telephone non valide"));
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
            'numtel' => Yii::t('app', 'Telephone'),
            'activite' => Yii::t('app', 'Activite'),
            'estPremium' => Yii::t('app', 'Est Premium'),
            'gallery_photos' => Yii::t('app', 'Gallery Photos'),
            'gallery_pdf' => Yii::t('app', 'Gallery Pdf'),
            'siteweb' => Yii::t('app', 'Siteweb'),
            'facebook' => Yii::t('app', 'Facebook'),
            'twitter' => Yii::t('app', 'Twitter'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryPdf()
    {
        return $this->hasOne(Gallery::className(), ['id' => 'gallery_pdf']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleryPhotos()
    {
        return $this->hasOne(Gallery::className(), ['id' => 'gallery_photos']);
    }

    /**
     * @inheritdoc
     * @return FournisseurQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FournisseurQuery(get_called_class());
    }
    
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $dir = "./uploads/fournisseurs/".$this->dossier."/";
            FileHelper::removeDirectory($dir);
            return true;
        }
        return false;
    }
    
    
    public function uploadLogo()
    {   
        $chemin = "uploads/fournisseurs/".$this->dossier."/";

        if ( ! is_dir($chemin)) {
            mkdir($chemin, 0777, true);
        }
        if ($this->fichier_logo) {
            $chemin_upload = $chemin."logo.".$this->fichier_logo->extension;
            $this->fichier_logo->saveAs($chemin_upload);
            $this->logo = "/logo.".$this->fichier_logo->extension;

            $this->offsetUnset('fichier_logo');
        }
        if ($this->validate()) {
            $this->save();
            return true;
        }
        
        //var_dump($this->logo);
        //Yii::$app->end();
            
        
        return false;
    }
    
    
    
}
