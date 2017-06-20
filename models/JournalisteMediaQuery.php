<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[JournalisteMedia]].
 *
 * @see JournalisteMedia
 */
class JournalisteMediaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return JournalisteMedia[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return JournalisteMedia|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
