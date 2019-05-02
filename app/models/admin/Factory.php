<?php

namespace app\models\admin;

use app\models\admin\AdminEntity;
use app\models\admin\AdminValidator;
use app\models\admin\AdminData;

class Factory
{

    /**
     * @var AdminValidator
     */
    private $adminValidator = null;

    /**
     * @var AdminData
     */
    private $adminData = null;

    /**
     * @var Factory
     */
    private static $instance = null;

    /**
     * @return Factory
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
    }

    /**
     * @return AdminEntity
     */
    public function createEntity()
    {
        return new AdminEntity();
    }

    /**
     * @return AdminValidator
     */
    public function getAdminValidator()
    {
        if ($this->adminValidator === null) {
            $this->adminValidator = new AdminValidator();
        }
        return $this->adminValidator;
    }

    /**
     * @return AdminData
     */
    public function getAdminData()
    {
        if ($this->adminData === null) {
            $this->adminData = new AdminData();
        }
        return $this->adminData;
    }
}
