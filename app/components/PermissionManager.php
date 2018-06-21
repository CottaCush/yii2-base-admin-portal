<?php

namespace app\components;

use app\models\Permission;
use app\models\Role;
use app\models\Status;
use cottacush\rbac\BasePermissionManager;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class PermissionManager
 * @package app\components
 * @author Olawale Lawal <wale@cottacush.com>
 */
class PermissionManager extends BasePermissionManager
{
    const KEY_ACTIVE_STATUS = 'active_status';
    const KEY_USER_ROLE = '_user_role';

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getRoles()
    {
        return Role::find()->asArray()->all();
    }

    /**
     * Gets a role using the role key
     * @author Olawale Lawal <wale@cottacush.com>
     * @param $key
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getRole($key)
    {
        $cache = Yii::$app->cache;
        $role = $cache->get($key);
        if (!$role) {
            $role = Role::find()->where(['key' => $key, 'status' => Status::STATUS_ACTIVE])->limit(1)->one();
            $cache->set($key, $role, Yii::$app->params['defaultCacheTimeout']);
        }
        return $role;
    }

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @param $roleId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getRoleById($roleId)
    {
        return Role::find()->where(['id' => $roleId, 'status' => Status::STATUS_ACTIVE])->limit(1)->one();
    }

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @param $permissionId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getPermissionById($permissionId)
    {
        return Permission::find()->where(['id' => $permissionId, 'status' => Status::STATUS_ACTIVE])
            ->limit(1)->one();
    }

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @return array|mixed|null|\yii\db\ActiveRecord
     * @throws \Exception
     * @throws \Throwable
     */
    public function getUserRole()
    {
        $user = Yii::$app->getUser()->getIdentity();
        $roleKey = ArrayHelper::getValue($user, 'role.key');
        return $this->getRole($roleKey);
    }

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @return array|mixed|\yii\db\ActiveRecord[]
     * @throws \Exception
     * @throws \Throwable
     */
    public function getUserPermissions()
    {
        /** @var Role $userRole */
        $userRole = $this->getUserRole();
        return $userRole ? $userRole->getCachedPermissions() : [];
    }

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @param $permissionKey
     * @return bool|mixed
     * @throws \Exception
     * @throws \Throwable
     */
    public function canAccess($permissionKey)
    {
        $permissions = array_column($this->getUserPermissions(), 'key');
        return in_array($permissionKey, $permissions);
    }

    /**
     * @author Olawale Lawal <wale@cottacush.com>
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getPermissions()
    {
        return Permission::find()->where(['status' => 1])->asArray()->all();
    }

    /**
     * @author Adegoke Obasa <goke@cottacush.com>
     * @param $key
     * @return mixed
     */
    public function getPermission($key)
    {
        // TODO: Implement getPermission() method.
        return true;
    }
}
