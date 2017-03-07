<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Langue]].
 *
 * @see Langue
 */
class LangueQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Langue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Langue|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
