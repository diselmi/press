<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "role".
 *
 * @property integer $id
 * @property string $type
 * @property string $nom
 * @property boolean $user_gerer
 * @property boolean $prestataire_gerer
 * @property boolean $site_gerer
 * @property boolean $contact_gerer
 *
 * @property User[] $users
 */
class Role extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nom', 'type'], 'required'],
            [['nom'], 'string', 'max' => 32],
            [['type'], 'string', 'max' => 16],
            [['user_gerer', 'prestataire_gerer', 'site_gerer', 'contact_gerer', 'evenement_gerer'], 'boolean'],
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
            'nom' => Yii::t('app', 'Nom'),
            'user_gerer' => Yii::t('app', 'Gerer les utilisateurs'),
            'prestataire_gerer' => Yii::t('app', 'Gerer prestataires'),
            'site_gerer' => Yii::t('app', 'Gerer le site'),
            'contact_gerer' => Yii::t('app', 'Gerer les emails de contact'),
            'evenement_gerer' => Yii::t('app', 'Gerer les evenements'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['role' => 'id']);
    }

    /**
     * @inheritdoc
     * @return RoleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RoleQuery(get_called_class());
    }
}
