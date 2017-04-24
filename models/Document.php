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
 * @property string $cree_le
 * @property integer $cree_par
 *
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
            [['chemin'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 256],
            [['cree_par'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['cree_par' => 'id']],
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
    
    
    public function upload()
    {
        $user_mail = Yii::$app->user->identity['mail'];
        
        //$chemin = "uploads/".md5($this->mail);
        $chemin = "uploads/".md5($user_mail)."/files/";

        if ( ! is_dir($chemin)) {
            mkdir($chemin);
        }

        if ($this->fichier) {
            //$chemin = "uploads/".$user_mail."/files/".$this->fichier->baseName;
            $this->fichier->saveAs($chemin.$this->fichier->baseName.".".$this->fichier->extension);
            $this->chemin = $chemin;

            /*$this->type = "document";
            if (str_pos($this->fichier->type, "image")) { $this->type = "image"; }
            if (str_pos($this->fichier->type, "image")) { $this->type = "image"; }*/
            $this->type = $this->fichier->type;

            $this->offsetUnset('fichier');
            if ($this->validate()) {
                $this->save();
                return true;
            }
        } 
            
        var_dump($this->errors);
        //Yii::trace($this->errors);
        return false;
    }
    
    
    
}
