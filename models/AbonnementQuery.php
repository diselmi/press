<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Abonnement]].
 *
 * @see Abonnement
 */
class AbonnementQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Abonnement[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Abonnement|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

}
