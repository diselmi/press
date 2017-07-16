<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "temoignage".
 *
 * @property integer $id
 * @property string $auteur
 * @property string $contenu
 * @property string $image
 */
class Temoignage extends \yii\db\ActiveRecord
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
        return 'temoignage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auteur', 'contenu'], 'required'],
            [['auteur'], 'string', 'max' => 64],
            [['contenu'], 'string', 'max' => 400],
            [['image'], 'string', 'max' => 256],
            [['fichier_image'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'auteur' => Yii::t('app', 'Auteur'),
            'contenu' => Yii::t('app', 'Contenu'),
            'image' => Yii::t('app', 'Image'),
        ];
    }

    /**
     * @inheritdoc
     * @return TemoignageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TemoignageQuery(get_called_class());
    }
    
    
    public function upload()
    {   
        $chemin = "uploads/temoignage-files/";

        if ( ! is_dir($chemin)) {
            mkdir($chemin);
        }
        if ($this->fichier_image) {
            $chemin_image = $chemin.md5("temoignage".$this->id).".".$this->fichier_image->extension;
            $this->fichier_image->saveAs($chemin_image);
            //var_dump($this->fichier_image);
            //Yii::$app->end();
            $this->image = "/".$chemin_image;

            $this->offsetUnset('fichier_image');
            if ($this->validate()) {
                $this->save();
                return true;
            }
        } 
            
        
        return false;
    }
    
    
}
