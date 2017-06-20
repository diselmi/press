<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document".
 *
 * @property integer $id
 * @property string $type
 * @property string $chemin
 * @property text $description
 * @property integer $gallery
 * @property string $cree_le
 * @property integer $cree_par
 *
 * @property Gallery $gallery0
 * @property User $creePar
 * @property Message[] $messages
 * @property Nouveaute[] $nouveautes
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $fichier;
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'chemin', 'cree_par'], 'required'],
            [['cree_par'], 'integer'],
            [['cree_le'], 'string'],
            [['type'], 'string', 'max' => 56],
            [['chemin'], 'string', 'max' => 256],
            [['description'], 'string', 'max' => 256],
            [['cree_par'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['cree_par' => 'id']],
            [['gallery'], 'exist', 'skipOnError' => true, 'targetClass' => Gallery::className(), 'targetAttribute' => ['gallery' => 'id']],
            [['fichier'], 'safe'],
            [['fichier'], 'file', 'extensions'=>'jpg, gif, png, doc, docx, pdf'],
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
            'chemin' => Yii::t('app', 'Chemin'),
            'description' => Yii::t('app', 'Description'),
            'cree_par' => Yii::t('app', 'Cree Par'),
            'cree_le' => Yii::t('app', 'Cree Le'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGallery0()
    {
        return $this->hasOne(Gallery::className(), ['id' => 'gallery']);
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
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['document' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNouveautes()
    {
        return $this->hasMany(Nouveaute::className(), ['image' => 'id']);
    }

    /**
     * @inheritdoc
     * @return DocumentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DocumentQuery(get_called_class());
    }
    
    public function beforeDelete() {
        
        if (parent::beforeDelete() && file_exists("./".$this->chemin)) {
            unlink("./".$this->chemin);
            return true;
        }
        return false;
    }
    
    
    public function upload($ch = null)
    {
        $user_mail = Yii::$app->user->identity['mail'];
        
        //$chemin = "uploads/".md5($this->mail);
        $chemin = $ch ? $ch : "uploads/".md5($user_mail)."/files/";

        if ( ! is_dir($chemin)) {
            mkdir($chemin);
        }

        if ($this->fichier) {
            $chemin_complet = $chemin.$this->fichier->baseName.".".$this->fichier->extension;
            $this->fichier->saveAs($chemin_complet);
            //$this->chemin = $chemin_complet;
            $this->chemin = $this->fichier->baseName.".".$this->fichier->extension;

            /*$this->type = "document";
            if (str_pos($this->fichier->type, "image")) { $this->type = "image"; }
            if (str_pos($this->fichier->type, "image")) { $this->type = "image"; }*/
            $this->type = $this->fichier->type;
            
            if (!$this->cree_par) {
                $this->cree_par = Yii::$app->user->id;
                $this->cree_le = date("Y-m-d H:i:s");
            }

            $this->offsetUnset('fichier');
            if ($this->validate()) {
                $this->save();
                return true;
            }
        } 
        
        
        //var_dump($this->errors);
        //Yii::trace($this->errors);
        return false;
    }
    
    
    
}
