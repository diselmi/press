<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "journaliste_media".
 *
 * @property integer $journaliste
 * @property integer $media
 * @property integer $tv
 * @property integer $radio
 * @property integer $j_papier
 * @property integer $j_electronique
 *
 * @property Journaliste $journaliste0
 * @property Media $media0
 */
class JournalisteMedia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'journaliste_media';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['journaliste', 'media'], 'required'],
            [['journaliste', 'media', 'tv', 'radio', 'j_papier', 'j_electronique'], 'integer'],
            [['journaliste'], 'exist', 'skipOnError' => true, 'targetClass' => Journaliste::className(), 'targetAttribute' => ['journaliste' => 'id']],
            [['media'], 'exist', 'skipOnError' => true, 'targetClass' => Media::className(), 'targetAttribute' => ['media' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'journaliste' => Yii::t('app', 'Journaliste'),
            'media' => Yii::t('app', 'Media'),
            'tv' => Yii::t('app', 'Tv'),
            'radio' => Yii::t('app', 'Radio'),
            'j_papier' => Yii::t('app', 'J Papier'),
            'j_electronique' => Yii::t('app', 'J Electronique'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJournaliste0()
    {
        return $this->hasOne(Journaliste::className(), ['id' => 'journaliste']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia0()
    {
        return $this->hasOne(Media::className(), ['id' => 'media']);
    }

    /**
     * @inheritdoc
     * @return JournalisteMediaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new JournalisteMediaQuery(get_called_class());
    }
}
