<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "langue".
 *
 * @property integer $id
 * @property string $nom
 *
 * @property User[] $users
 */
class Langue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'langue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nom'], 'required'],
            [['nom'], 'string', 'max' => 3],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['lang' => 'id']);
    }

    /**
     * @inheritdoc
     * @return LangueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LangueQuery(get_called_class());
    }
}
