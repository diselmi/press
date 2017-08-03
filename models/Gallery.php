<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "gallery".
 *
 * @property integer $id
 *
 * @property Document[] $documents
 * @property Fournisseur[] $fournisseurs
 * @property Fournisseur[] $fournisseurs0
 */
class Gallery extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFiles
     */
    public $fichiers;
    
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['gallery' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFournisseurs()
    {
        return $this->hasMany(Fournisseur::className(), ['gallery_pdf' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFournisseurs0()
    {
        return $this->hasMany(Fournisseur::className(), ['gallery_photos' => 'id']);
    }

    /**
     * @inheritdoc
     * @return GalleryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GalleryQuery(get_called_class());
    }
    
    public function deleteFiles()
    {
        foreach ($this->documents as $doc) {
            $doc->delete();
        }
    }
    
    
    public function upload($chemin, $types)
    {   
        //$chemin = "uploads/nouveaute-files/";
        //$chemin = "uploads/founisseurs/".md5($this->id)."/photos/";

        
        if ( ! is_dir($chemin)) {
            mkdir($chemin, 0777, true);
        }

        foreach ($this->fichiers as $fichier) {
            $type_valide = false;
            foreach ($types as $type) {
                if (strpos($fichier->type, $type) !== false) {$type_valide = true; }
                //else { var_dump(strpos($fichier->type, $type)); Yii::$app->end(); } 
            }
            
            
            if ($type_valide) {
                $doc = new Document();
                $doc->fichier = $fichier;
                //$doc->type = $fichier->type;
                $doc->gallery = $this->id;
                if (!(  $doc->upload($chemin)  )) {
                    //var_dump($doc);
                    //Yii::$app->end();
                    return false;
                }
            }else {
                $this->addError('type', 'Wrong file type: '.$fichier->type);
                //var_dump($fichier->type);
                //Yii::$app->end();
                return false;
            }
            
        } 

        $this->offsetUnset('fichiers');
        if ($this->validate()) {
            $this->save();
            return true;
        }
        return false;
            
        
        //return false;
    }
    
    
}
