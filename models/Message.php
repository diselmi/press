<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "message".
 *
 * @property integer $id
 * @property string $texte
 * @property integer $document
 * @property integer $expediteur
 * @property integer $destinataire
 * @property string $date_envoie
 * @property boolean $vu
 *
 * @property Document $document0
 * @property User $expediteur0
 * @property User $destinataire0
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['texte', 'expediteur', 'destinataire'], 'required'],
            [['document', 'expediteur', 'destinataire'], 'integer'],
            ['vu', 'boolean'],
            [['date_envoie'], 'safe'],
            [['texte'], 'string', 'max' => 256],
            [['document'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document' => 'id']],
            [['expediteur'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['expediteur' => 'id']],
            [['destinataire'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['destinataire' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'texte' => Yii::t('app', 'Texte'),
            'document' => Yii::t('app', 'Document'),
            'expediteur' => Yii::t('app', 'Expediteur'),
            'destinataire' => Yii::t('app', 'Destinataire'),
            'date_envoie' => Yii::t('app', 'Date Envoie'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument0()
    {
        return $this->hasOne(Document::className(), ['id' => 'document']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExpediteur0()
    {
        return $this->hasOne(User::className(), ['id' => 'expediteur']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDestinataire0()
    {
        return $this->hasOne(User::className(), ['id' => 'destinataire']);
    }

    /**
     * @inheritdoc
     * @return MessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MessageQuery(get_called_class());
    }
}
