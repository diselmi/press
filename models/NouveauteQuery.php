<?php

namespace app\models;

use Yii;

/**
 * This is the ActiveQuery class for [[Nouveaute]].
 *
 * @see Nouveaute
 */
class NouveauteQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Nouveaute[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Nouveaute|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    public function galleryQuery($db = null)
    {
        if (!$db) { $db = Yii::$app->db; }
        return $db->createCommand(
                    "select titre as title, image as href from nouveaute"
                )->queryAll();
    }
}
