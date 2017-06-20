<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "salle".
 *
 * @property integer $id
 * @property string $nom
 * @property string $image
 * @property integer $capacite
 * @property string $connectique
 * @property string $gouvernerat
 * @property string $adresse
 * @property string $vis_a_vis
 * @property string $numtel
 * @property integer $cree_par
 * @property boolean $est_premium
 * @property string $dossier
 *
 * @property User $creePar
 * @property Gallery $galleryPdf
 * @property Gallery $galleryPhotos
 */
class Salle extends \yii\db\ActiveRecord
{
    
    /**
     * @var UploadedFile
     */
    public $fichier_image;
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'salle';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nom', 'gouvernerat', 'adresse'], 'required'],
            [['capacite', 'cree_par'], 'integer'],
            [['nom', 'vis_a_vis'], 'string', 'max' => 64],
            [['numtel'], 'string', 'max' => 32],
            [['connectique', 'gouvernerat'], 'string', 'max' => 12],
            [['adresse'], 'string', 'max' => 256],
            [['est_premium'], 'boolean'],
            [['cree_par'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['cree_par' => 'id']],
            [['fichier_image'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 4],
            [['gallery_pdf'], 'exist', 'skipOnError' => true, 'targetClass' => Gallery::className(), 'targetAttribute' => ['gallery_pdf' => 'id']],
            [['gallery_photos'], 'exist', 'skipOnError' => true, 'targetClass' => Gallery::className(), 'targetAttribute' => ['gallery_photos' => 'id']],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nom' => Yii::t('app', 'Nom'),
            'capacite' => Yii::t('app', 'Capacite'),
            'connectique' => Yii::t('app', 'Connectique'),
            'gouvernerat' => Yii::t('app', 'Gouvernerat'),
            'adresse' => Yii::t('app', 'Adresse'),
            'vis_a_vis' => Yii::t('app', 'Vis A Vis'),
            'numtel' => Yii::t('app', 'Telephone'),
            'est_premium' => Yii::t('app', 'Premium'),
            'cree_par' => Yii::t('app', 'Cree Par'),
            'gallery_photos' => Yii::t('app', 'Gallery Photos'),
            'gallery_pdf' => Yii::t('app', 'Gallery Pdf'),
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
     * @return SalleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SalleQuery(get_called_class());
    }
    
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            $dir = "./uploads/salles/".$this->dossier."/";
            FileHelper::removeDirectory($dir);
            return true;
        }
        return false;
    }
    
    public function uploadImage()
    {   
        $chemin = "uploads/salles/".$this->dossier."/";

        if ( ! is_dir($chemin)) {
            mkdir($chemin, 0777, true);
        }
        if ($this->fichier_image) {
            $chemin_upload = $chemin."image.".$this->fichier_image->extension;
            $this->fichier_image->saveAs($chemin_upload);
            $this->image = "/image.".$this->fichier_image->extension;

            $this->offsetUnset('fichier_image');
        }
        if ($this->validate()) {
            $this->save();
            return true;
        }
        
        //var_dump($this->image);
        //Yii::$app->end();
            
        
        return false;
    }
    
    
    
}
