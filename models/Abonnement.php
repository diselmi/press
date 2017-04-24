<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "abonnement".
 *
 * @property integer $id
 * @property integer $client
 * @property string $date_debut
 * @property string $date_fin
 * @property string $etat
 * @property integer $vis_a_vis
 * @property integer $acces_salles
 * @property integer $acces_journalistes
 *
 * @property User $client0
 * @property User $vis_a_vis0
 */
class Abonnement extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'abonnement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['client', 'date_debut', 'date_fin', 'vis_a_vis'], 'required'],
            [['client', 'vis_a_vis', 'acces_salles', 'acces_journalistes'], 'integer'],
            [['etat'], 'string'],
            [['date_debut', 'date_fin'], 'safe'],
            //[['client0'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['client' => 'id']],
            [['vis_a_vis'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['vis_a_vis' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'client' => Yii::t('app', 'Client'),
            'date_debut' => Yii::t('app', 'Date Debut'),
            'date_fin' => Yii::t('app', 'Date Echeance'),
            'etat' => Yii::t('app', 'Etat'),
            'vis_a_vis' => Yii::t('app', 'Vis A Vis'),
            'acces_salles' => Yii::t('app', 'Acces Salles'),
            'acces_journalistes' => Yii::t('app', 'Acces Journalistes'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient0()
    {
        return $this->hasOne(User::className(), ['id' => 'client']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVis_a_vis0()
    {
        return $this->hasOne(User::className(), ['id' => 'vis_a_vis']);
    }

    /**
     * @inheritdoc
     * @return AbonnementQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AbonnementQuery(get_called_class());
    }
}
