<?php

namespace app\controllers;

use app\App;
use app\lib\WebController;
use app\models\task\Factory;
use app\models\admin\Factory as AdminFactory;
use Zend\Diactoros\Response\RedirectResponse;

class Site extends WebController
{
    /**
     * @var \app\models\admin\AdminEntity
     */
    protected $admin = null;

    /**
     * @return Site
     */
    public function __construct()
    {
        if (isset(App::getInstance()->getSessionContainer()->authKey)) {
            $authKey = App::getInstance()->getSessionContainer()->authKey;
            $this->admin = AdminFactory::getInstance()->getAdminData()->getAdminByAuthKey($authKey);
        }
    }

    /**
     * @param $page integer
     * @return string
     */
    public function index($page = 1)
    {
        $url = App::getInstance()
            ->getRouterContainer()
            ->getGenerator()
            ->generate('index.page', ['page' => $page]);
        App::getInstance()->getSessionContainer()->redirectUrl = $url;
        $perPage = 3;
        $validateLayer = Factory::getInstance()->getTaskValidator();
        $dataLayer = Factory::getInstance()->getTaskData();
        $queryParams = App::getInstance()->getRequest()->getQueryParams();
        $sortedBy = $queryParams['sortedBy'] ?? null;
        $sortedDirect = $queryParams['direct'] ?? null;
        $tasks = $validateLayer->getTasksList($page, $perPage, $sortedBy, $sortedDirect);
        if ($tasks === false) {
            return 'Hacker go away !!!';
        }
        $tasksCount = $dataLayer->getTasksListCount();
        $params = [
            'tasks' => $tasks,
            'currentPage' => $page,
            'listCount' => $tasksCount,
            'perPage' => $perPage,
            'sortedBy' => $sortedBy,
            'sortedDirect' => $sortedDirect,
            'isAdmin' => $this->admin !== null,
        ];
        return $this->load_view('site/tasksList.php', $params);
    }

    /**
     * @return string
     */
    public function addTask()
    {
        $paramsViews = [
            'errors' => [],
            'task' => null,
            'values' => []
        ];
        if (App::getInstance()->getRequest()->getMethod() == 'POST') {
            $postParams = App::getInstance()->getRequest()->getParsedBody();
            $taskValidator = Factory::getInstance()->getTaskValidator();
            if (!$taskValidator->addTask($postParams['userName'], $postParams['email'], $postParams['description'])) {
                $paramsViews['errors'] = $taskValidator->getErrors();
                $paramsViews['values'] = $postParams;
            } else {
                $backUrl = App::getInstance()->getRouterContainer()->getGenerator()->generate('index');
                return new RedirectResponse($backUrl);
            }
        }
        return $this->load_view('site/taskForm.php', $paramsViews);
    }

    /**
     * @param $taskId integer
     * @return string|RedirectResponse
     */
    public function editTask($taskId)
    {
        if ($this->admin === null) {
            $backUrl = App::getInstance()->getRouterContainer()->getGenerator()->generate('index');
            return new RedirectResponse($backUrl);
        }
        $paramsViews = [
            'errors' => [],
            'task' => null,
            'values' => []
        ];
        $taskValidator = Factory::getInstance()->getTaskValidator();
        $editedTask = $taskValidator->getTaskFromId($taskId);
        if ($editedTask) {
            $paramsViews['task'] = $editedTask;
        }
        if (App::getInstance()->getRequest()->getMethod() == 'POST') {
            $postParams = App::getInstance()->getRequest()->getParsedBody();
            $taskValidator = Factory::getInstance()->getTaskValidator();
            if (!$taskValidator->editTask($taskId, $postParams['description'])) {
                $paramsViews['errors'] = $taskValidator->getErrors();
                $paramsViews['values'] = $postParams;
            } else {
                $backUrl = App::getInstance()->getSessionContainer()->redirectUrl ?? App::getInstance()->getRouterContainer()->getGenerator()->generate('index');
                return new RedirectResponse($backUrl);
            }
        }
        return $this->load_view('site/taskForm.php', $paramsViews);
    }

    /**
     * @param $taskId
     * @return RedirectResponse
     */
    public function setStatus($taskId)
    {
        if ($this->admin === null) {
            $backUrl = App::getInstance()->getRouterContainer()->getGenerator()->generate('index');
            return new RedirectResponse($backUrl);
        }
        $taskValidator = Factory::getInstance()->getTaskValidator();
        $editedTask = $taskValidator->getTaskFromId($taskId);
        if ($editedTask) {
            $taskDataLayer = Factory::getInstance()->getTaskData();
            $taskDataLayer->switchStatus($editedTask);
        }
        $backUrl = App::getInstance()->getSessionContainer()->redirectUrl ?? App::getInstance()->getRouterContainer()->getGenerator()->generate('index');
        return new RedirectResponse($backUrl);
    }
}
