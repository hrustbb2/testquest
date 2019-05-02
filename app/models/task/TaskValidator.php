<?php

namespace app\models\task;

use app\lib\BaseValidator;
use Respect\Validation\Validator as v;
use app\models\task\Factory;

class TaskValidator extends BaseValidator
{
    const GET_TASKS_LIST = 10;

    const ADD_TASK = 20;

    const GET_TASK = 30;

    const EDIT_TASK = 40;

    /**
     * @var integer
     */
    private $state;

    /**
     * @return Array
     */
    private function getRulesForGetTasksList()
    {
        v::with('app\\models\\task\\validators\\');
        $rules = [
            'page' => [
                [
                    'validator' => v::numeric()->positive(),
                    'message' => 'Parametr is invalid.'
                ],
            ],
            'sortedBy' => [
                [
                    'validator' => v::SortBy(),
                    'message' => 'Parametr is invalid.'
                ],
            ],
            'sortedDirect' => [
                [
                    'validator' => v::SortDirect(),
                    'message' => 'Parametr is invalid.'
                ],
            ]
        ];
        return $rules;
    }

    /**
     * @return Array
     */
    private function getRulesForAddTask()
    {
        $rules = [
            'userName' => [
                [
                    'validator' => v::notEmpty(),
                    'message' => 'Required field',
                ],
                [
                    'validator' => v::length(null, 45),
                    'message' => 'Length most be < 45',
                ],
            ],
            'email' => [
                [
                    'validator' => v::notEmpty()->length(null, 45)->email(),
                    'message' => 'Required, email and length < 45',
                ],
            ],
            'description' => [
                [
                    'validator' => v::notEmpty(),
                    'message' => 'Required required',
                ],
            ]
        ];
        return $rules;
    }

    /**
     * @return Array
     */
    private function getRulesForEditTask()
    {
        $rules = [
            'id' => [
                [
                    'validator' => v::numeric()->positive(),
                    'message' => 'id must be number',
                ],
            ],
            'description' => [
                [
                    'validator' => v::notEmpty(),
                    'message' => 'Required required',
                ],
            ]
        ];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getRules()
    {
        $rules = [];

        switch ($this->state) {
            case self::GET_TASKS_LIST:
                $rules = $this->getRulesForGetTasksList();
                break;
            case self::ADD_TASK:
                $rules = $this->getRulesForAddTask();
                break;
            case self::GET_TASK:
                $rules = [
                    'id' => [
                        [
                            'validator' => v::numeric()->positive(),
                            'message' => 'id must be number',
                        ],
                    ]
                ];
                break;
            case self::EDIT_TASK:
                $rules = $this->getRulesForEditTask();
                break;
        }
        return $rules;
    }

    /**
     * @param $page integer
     * @param $perpage integer
     * @param $sortedBy string|null
     * @param $sortedDirect string|null
     * @return false|\app\models\task\TaskEntity[]
     */
    public function getTasksList($page, $perpage, $sortedBy, $sortedDirect)
    {
        $attr = [
            'page' => $page,
            'sortedBy' => $sortedBy,
            'sortedDirect' => $sortedDirect
        ];
        $this->state = self::GET_TASKS_LIST;
        if ($this->validate($attr)) {
            $dataLayer = Factory::getInstance()->getTaskData();
            return $dataLayer->getTasksList($page, $perpage, $sortedBy, $sortedDirect);
        }
        return false;
    }

    /**
     * @param $userName string
     * @param $email string
     * @param $description string
     * @return false|\app\models\task\TaskEntity
     */
    public function addTask($userName, $email, $description)
    {
        $attr = [
            'userName' => $userName,
            'email' => $email,
            'description' => $description,
        ];
        $this->state = self::ADD_TASK;
        if ($this->validate($attr)) {
            $newTask = Factory::getInstance()->createTaskEntity();
            $newTask->load($attr);
            $dataLayer = Factory::getInstance()->getTaskData();
            return $dataLayer->addTask($newTask);
        }
        return false;
    }

    /**
     * @param $taskId integer
     * @return false|\app\models\task\TaskEntity
     */
    public function getTaskFromId($taskId)
    {
        $attr = [
            'id' => $taskId
        ];
        $this->state = self::GET_TASK;
        if ($this->validate($attr)) {
            $dataLayer = Factory::getInstance()->getTaskData();
            return $dataLayer->getTaskFromId($taskId);
        }
        return false;
    }

    /**
     * @param $taskId integer
     * @param $userName string
     * @param $email string
     * @param $description string
     * @return false|\app\models\task\TaskEntity
     */
    public function editTask($taskId, $description)
    {
        $attr = [
            'id' => $taskId,
            'description' => $description,
        ];
        $this->state = self::EDIT_TASK;
        if ($this->validate($attr)) {
            $editedTask = Factory::getInstance()->createTaskEntity();
            $editedTask->load($attr);
            $dataLayer = Factory::getInstance()->getTaskData();
            return $dataLayer->editTask($editedTask);
        }
        return false;
    }
}
