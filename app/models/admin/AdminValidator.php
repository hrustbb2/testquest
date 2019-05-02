<?php

namespace app\models\admin;

use app\lib\BaseValidator;
use Respect\Validation\Validator as v;
use app\models\admin\Factory;

class AdminValidator extends BaseValidator
{
    /**
     * @inheritdoc
     */
    public function getRules()
    {
        return [
            'login' => [
                [
                    'validator' => v::notEmpty(),
                    'message' => 'Required field',
                ]
            ],
            'password' => [
                [
                    'validator' => v::notEmpty(),
                    'message' => 'Required field',
                ]
            ],

        ];
    }

    /**
     * @param $login string
     * @param $login string
     * @param $password string
     * @return false|\app\models\admin\AdminEntity
     */
    public function getAuthKey($login, $password)
    {
        $attr = [
            'login' => $login,
            'password' => $password
        ];
        if ($this->validate($attr)) {
            $adminData = Factory::getInstance()->getAdminData();
            $adminEntity = $adminData->getAdminByLogin($login);
            if ($adminEntity !== null && $adminEntity->validatePassword($password)) {
                $adminEntity->authKey = $adminData->updateAuthKey($adminEntity);
                return $adminEntity;
            } else {
                $this->addError('password', 'Wrong password');
            }
        }
        return false;
    }
}
