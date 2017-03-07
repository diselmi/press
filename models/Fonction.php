<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fonction".
 *
 * @property integer $id
 * @property string $nom
 *
 * @property User[] $users
 */
class Fonction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fonction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nom'], 'required'],
            [['nom'], 'string', 'max' => 32],
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
        return $this->hasMany(User::className(), ['fonction' => 'id']);
    }

    /**
     * @inheritdoc
     * @return FonctionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FonctionQuery(get_called_class());
    }
}
