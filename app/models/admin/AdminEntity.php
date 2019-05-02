<?php

namespace app\models\admin;

use app\lib\BaseEntity;

class AdminEntity extends BaseEntity
{
    /**
     * @var string
     */
    private $salt1 = 'ereRIbSgr445';

    /**
     * @var string
     */
    private $salt2 = 'feRJUj67f';

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $passwordHash;

    /**
     * @var string
     */
    public $authKey;

    /**
     * @param $password string
     * @return void
     */
    public function setPasswordHash($password)
    {
        $this->passwordHash = hash('sha512', $this->salt1 . $password . $this->salt2);
    }

    /**
     * @param $password string
     * @return string
     */
    public function validatePassword($password)
    {
        $hash = hash('sha512', $this->salt1 . $password . $this->salt2);
        return $this->passwordHash == strtoupper($hash);
    }
}
