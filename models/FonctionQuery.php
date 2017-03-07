<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Fonction]].
 *
 * @see Fonction
 */
class FonctionQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Fonction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Fonction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
