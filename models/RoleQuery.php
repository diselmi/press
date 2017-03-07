<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Role]].
 *
 * @see Role
 */
class RoleQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Role[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Role|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    /**
     * @return Role|array|null
     */
    public function adminRoles($db = null)
    {
        $query = $this->andWhere(['type' => '1']);
        return $query;
    }
    
    /**
     * @return Role|array|null
     */
    public function userRoles($db = null)
    {
        $query = $this->andWhere(['type' => '2']);
        return $query;
    }
    
}
