<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "nouveaute".
 *
 * @property integer $id
 * @property string $titre
 * @property string $libelle_court
 * @property string $libelle_long
 * @property string $image
 * @property datetime $cree_le
 * @property integer $cree_par
 *
 * @property User $creePar
 * @property Document $image0
 */
class Nouveaute extends \yii\db\ActiveRecord
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
        return 'nouveaute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['titre', 'libelle_court', 'libelle_long'], 'required'],
            [['cree_le'], 'safe'],
            [['cree_par'], 'integer'],
            [['titre'], 'string', 'max' => 64],
            [['libelle_court'], 'string', 'max' => 256],
            [['libelle_long'], 'string', 'max' => 500],
            [['cree_par'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['cree_par' => 'id']],
            [['fichier_image'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'titre' => Yii::t('app', 'Titre'),
            'libelle_court' => Yii::t('app', 'Libelle Court'),
            'libelle_long' => Yii::t('app', 'Libelle Long'),
            'date_creation' => Yii::t('app', 'Date Creation'),
            'image' => Yii::t('app', 'Image'),
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
    public function getImage0()
    {
        return $this->hasOne(Document::className(), ['id' => 'image']);
    }

    /**
     * @inheritdoc
     * @return NouveauteQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NouveauteQuery(get_called_class());
    }
    
    
    public function upload()
    {   
        $chemin = "uploads/nouveaute-files/";

        if ( ! is_dir($chemin)) {
            mkdir($chemin);
        }
        if ($this->fichier_image) {
            //$chemin = "uploads/".$user_mail."/files/".$this->fichier->baseName;
            //$chemin_image = $chemin.md5("nouveaute".$this->id).$this->fichier_image->baseName.".".$this->fichier_image->extension;
            $chemin_image = $chemin.md5("nouveaute".$this->id).".".$this->fichier_image->extension;
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
