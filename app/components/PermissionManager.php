<?php

namespace app\components;

use app\models\Permission;
use app\models\Role;
use app\models\Status;
use cottacush\rbac\BasePermissionManager;
use Throwable;
use Yii;
use yii\db\ActiveRecord;
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
     * @return array
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function getRoles(): array
    {
        return Role::find()->asArray()->all();
    }

    /**
     * Gets a role using the role key
     * @param $key
     * @return array|null|ActiveRecord
     *@author Olawale Lawal <wale@cottacush.com>
     */
    public function getRole($key): array|null|ActiveRecord
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
     * @param $roleId
     * @return array|null|ActiveRecord
     *@author Olawale Lawal <wale@cottacush.com>
     */
    public function getRoleById($roleId): array|null|ActiveRecord
    {
        return Role::find()->where(['id' => $roleId, 'status' => Status::STATUS_ACTIVE])->limit(1)->one();
    }

    /**
     * @param $permissionId
     * @return array|null|ActiveRecord
     *@author Olawale Lawal <wale@cottacush.com>
     */
    public function getPermissionById($permissionId): array|null|ActiveRecord
    {
        return Permission::find()->where(['id' => $permissionId, 'status' => Status::STATUS_ACTIVE])
            ->limit(1)->one();
    }

    /**
     * @return array|ActiveRecord|null
     * @throws Throwable
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function getUserRole(): array|null|ActiveRecord
    {
        $user = Yii::$app->getUser()->getIdentity();
        $roleKey = ArrayHelper::getValue($user, 'role.key');
        return $this->getRole($roleKey);
    }

    /**
     * @return array
     * @throws Throwable
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function getUserPermissions(): array
    {
        /** @var Role $userRole */
        $userRole = $this->getUserRole();
        return $userRole ? $userRole->getCachedPermissions() : [];
    }

    /**
     * @param $permissionKey
     * @return bool
     * @throws Throwable
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function canAccess($permissionKey): bool
    {
        $permissions = array_column($this->getUserPermissions(), 'key');
        return in_array($permissionKey, $permissions);
    }

    /**
     * @return array
     * @author Olawale Lawal <wale@cottacush.com>
     */
    public function getPermissions(): array
    {
        return Permission::find()->where(['status' => 1])->asArray()->all();
    }

    /**
     * @param $key
     * @return bool
     *@author Adegoke Obasa <goke@cottacush.com>
     */
    public function getPermission($key): bool
    {
        // TODO: Implement getPermission() method.
        return true;
    }
}
