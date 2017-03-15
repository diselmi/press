<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Admin]].
 *
 * @see Admin
 */
class AdminQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Admin[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Admin|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    public function onlyAdmins($db = null)
    {
        $query = $this->joinWith('role0');
        $query->andWhere(['=', 'type', 'client']);
        return $query;
    }
}
