<?php

namespace app\models\task;

use app\models\task\TaskData;
use app\models\task\TaskEntity;
use app\models\task\TaskValidator;

class Factory
{

    /**
     * @var Factory
     */
    private static $instance = null;

    /**
     * @var TaskData
     */
    private $taskData = null;

    /**
     * @var TaskValidator
     */
    private $taskValidator = null;

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
     * @return TaskData
     */
    public function getTaskData()
    {
        if ($this->taskData === null) {
            $this->taskData = new TaskData();
        }
        return $this->taskData;
    }

    /**
     * @return TaskEntity
     */
    public function createTaskEntity()
    {
        return new TaskEntity();
    }

    /**
     * @return TaskValidator
     */
    public function getTaskValidator()
    {
        if ($this->taskValidator === null) {
            $this->taskValidator = new TaskValidator();
        }
        return $this->taskValidator;
    }
}
