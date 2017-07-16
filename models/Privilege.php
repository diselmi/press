<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "privilege".
 *
 * @property integer $id
 * @property string $titre
 * @property string $libelle_court
 * @property string $libelle_long
 * @property integer $cree_par
 *
 * @property User $creePar
 */
class Privilege extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'privilege';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['titre', 'libelle_court', 'libelle_long'], 'required'],
            [['cree_par'], 'integer'],
            [['titre'], 'string', 'max' => 64],
            [['libelle_court'], 'string', 'max' => 256],
            [['libelle_long'], 'string', 'max' => 500],
            [['cree_par'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['cree_par' => 'id']],
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
     * @inheritdoc
     * @return PrivilegeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PrivilegeQuery(get_called_class());
    }
}
