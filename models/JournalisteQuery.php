<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Journaliste]].
 *
 * @see Journaliste
 */
class JournalisteQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Journaliste[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Journaliste|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
