<?php

namespace app\models\admin;

use app\App;
use Pixie\QueryBuilder\QueryBuilderHandler;
use app\models\admin\Factory;

class AdminData
{
    /**
     * @var QueryBuilderHandler
     */
    private $queryBuilder;

    /**
     * @var string
     */
    private $adminsTable = 'admins';

    /**
     * @return AdminData
     */
    public function __construct()
    {
        $dbConnect = App::getInstance()->getDb();
        $this->queryBuilder = new QueryBuilderHandler($dbConnect);
    }

    /**
     * @param $login string
     * @return null|\app\models\admin\AdminEntity
     */
    public function getAdminByLogin($login)
    {
        $adminData = $this->queryBuilder
            ->table($this->adminsTable)
            ->select(['id', 'login', 'passwordHash', 'authKey'])
            ->where('login', $login)
            ->get();
        if (!empty($adminData)) {
            $adminEntity = Factory::getInstance()->createEntity();
            $adminEntity->load($adminData[0]);
            return $adminEntity;
        }
        return null;
    }

    /**
     * @param $adminEntity \app\models\admin\AdminEntity
     * @return string
     */
    public function updateAuthKey($adminEntity)
    {
        $newAuthKey = bin2hex(random_bytes(16));
        $this->queryBuilder
            ->table($this->adminsTable)
            ->where('id', $adminEntity->id)
            ->update([
                'authKey' => $newAuthKey
            ]);
        return $newAuthKey;
    }

    /**
     * @param $authKey string
     * @return null|\app\models\admin\AdminEntity
     */
    public function getAdminByAuthKey($authKey)
    {
        $adminData = $this->queryBuilder
            ->table($this->adminsTable)
            ->select(['id', 'login', 'passwordHash', 'authKey'])
            ->where('authKey', $authKey)
            ->get();
        if (!empty($adminData)) {
            $adminEntity = Factory::getInstance()->createEntity();
            $adminEntity->load($adminData[0]);
            return $adminEntity;
        }
        return null;
    }
}
