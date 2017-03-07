<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $nom
 * @property string $prenom
 * @property string $mail
 * @property string $login
 * @property string $pass
 * @property integer $role
 * @property string $photo
 *
 * @property Langue $lang
 * @property Role $role
 */
class Admin extends \yii\db\ActiveRecord
{
    
    /**
     * @var UploadedFile
     */
    public $imagePhoto;
    
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nom', 'mail', 'login', 'pass', 'role'], 'required'],
            [['nom', 'prenom', 'mail', 'login', 'pass', 'fonction'], 'string', 'max' => 32],
            [['logo'], 'string', 'max' => 256],
            [['adresse'], 'string', 'max' => 64],
            [['mail'], 'unique'],
            [['lang'], 'exist', 'skipOnError' => true, 'targetClass' => Langue::className(), 'targetAttribute' => ['lang' => 'id']],
            [['imagePhoto'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            
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
            'prenom' => Yii::t('app', 'Prenom'),
            'mail' => Yii::t('app', 'Mail'),
            'login' => Yii::t('app', 'Login'),
            'pass' => Yii::t('app', 'Pass'),
            'lang' => Yii::t('app', 'Lang'),
            'photo' => Yii::t('app', 'Photo'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang0()
    {
        return $this->hasOne(Langue::className(), ['id' => 'lang']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole0()
    {
        return $this->hasOne(Role::className(), ['id' => 'role']);
    }

    /**
     * @inheritdoc
     * @return AdminQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdminQuery(get_called_class());
    }
    
    
    public function upload()
    {
        if ($this->validate()) {
            $chemin = "uploads/".md5($this->mail);
                
            if ( ! is_dir($chemin."/")) {
                mkdir($chemin."/");
            }
            //$this->imageFile->saveAs('uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            //var_dump($this->attributes);
            //Yii::$app->end();
            
            
            if ($this->imagePhoto) {
                $cheminPhoto = $chemin."/photo.".$this->imagePhoto->extension;
                $this->imagePhoto->saveAs($cheminPhoto);
                $this->photo = "/".$cheminPhoto;
                $this->offsetUnset('imagePhoto');
                //var_dump($chemin);
            }
            
            //var_dump($this->imagePhoto);
                //Yii::$app->end();
            
            $this->save();
            return true;
            
            
        } else {
            //var_dump($this->errors);
            //Yii::trace($this->errors);
            return false;
        }
    }
    
    
    
}
