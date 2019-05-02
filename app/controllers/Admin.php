<?php

namespace app\controllers;

use app\App;
use app\lib\WebController;
use app\models\admin\Factory;
use Zend\Diactoros\Response\RedirectResponse;

class Admin extends WebController
{
    /**
     * @return string|RedirectResponse
     */
    public function login()
    {
        $params = [
            'errors' => [],
            'vars' => []
        ];
        if (App::getInstance()->getRequest()->getMethod() == 'POST') {
            $postParams = App::getInstance()->getRequest()->getParsedBody();
            $params['vars'] = $postParams;
            $adminValidator = Factory::getInstance()->getAdminValidator();
            $adminEntity = $adminValidator->getAuthKey($postParams['login'], $postParams['password']);
            if ($adminEntity) {
                App::getInstance()->getSessionContainer()->authKey = $adminEntity->authKey;
                $backUrl = App::getInstance()->getRouterContainer()->getGenerator()->generate('index');
                return new RedirectResponse($backUrl);
            } else {
                $params['errors'] = $adminValidator->getErrors();
            }
        }
        return $this->load_view('admin/loginForm.php', $params);
    }

    /**
     * @return RedirectResponse
     */
    public function logout()
    {
        if(isset(App::getInstance()->getSessionContainer()->authKey)){
            unset(App::getInstance()->getSessionContainer()->authKey);
        }
        $backUrl = App::getInstance()->getRouterContainer()->getGenerator()->generate('index');
        return new RedirectResponse($backUrl);
    }
}
