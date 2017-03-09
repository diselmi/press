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
        $query = $this->andWhere(['type' => 'admin']);
        return $query;
    }
    
    /**
     * @return Role|array|null
     */
    public function userRoles($db = null)
    {
        $query = $this->andWhere(['type' => 'client']);
        return $query;
    }
    
    /**
     * @return Role|array|null
     */
    public function allRoles($db = null)
    {
        $query = $this->where(['!=', 'type', 'superadmin']);
        return $query;
    }
    
    /**
     * @return Role|array|null
     */
    public function allRolesTypes($db = null)
    {
        $query = Role::findBySql("select distinct type from role where type != 'superadmin' ");
        return $query;
    }
    
    
}
