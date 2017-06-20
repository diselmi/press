<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Temoignage]].
 *
 * @see Temoignage
 */
class TemoignageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Temoignage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Temoignage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
