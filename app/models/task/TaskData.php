<?php

namespace app\models\task;

use app\App;
use Pixie\QueryBuilder\QueryBuilderHandler;
use app\models\task\Factory;

class TaskData
{
    /**
     * @var QueryBuilderHandler
     */
    private $queryBuilder;

    /**
     * @var string
     */
    private $tasksTable = 'tasks';

    /**
     * @return TaskData
     */
    public function __construct()
    {
        $dbConnect = App::getInstance()->getDb();
        $this->queryBuilder = new QueryBuilderHandler($dbConnect);
    }

    /**
     * @param $page integer
     * @param $perpage integer
     * @param $sortedBy string
     * @param $sortedDirect string
     * @return TaskEntity[]
     */
    public function getTasksList($page, $perpage, $sortedBy, $sortedDirect)
    {
        $offset = ($page - 1) * $perpage;

        $query = $this->queryBuilder
            ->table($this->tasksTable)
            ->select(['id', 'userName', 'email', 'description', 'status'])
            ->limit($perpage)
            ->offset($offset);

        if ($sortedBy == 'name') {
            $query->orderBy('userName', $sortedDirect);
        }
        if ($sortedBy == 'email') {
            $query->orderBy('email', $sortedDirect);
        }
        if ($sortedBy == 'status') {
            $query->orderBy('status', $sortedDirect);
        }

        $query->orderBy('id', 'desc');

        $tasksArray = $query->get();
        $factory = Factory::getInstance();
        $tasksEntityes = [];
        foreach ($tasksArray as $item) {
            $taskEntity = $factory->createTaskEntity();
            $taskEntity->load($item);
            $tasksEntityes[] = $taskEntity;
        }
        return $tasksEntityes;
    }

    /**
     * @return integer
     */
    public function getTasksListCount()
    {
        $count = $this->queryBuilder->table($this->tasksTable)->count();
        return $count;
    }

    /**
     * @param $newTask \app\models\task\TaskEntity
     * @return \app\models\task\TaskEntity
     */
    public function addTask($newTask)
    {
        $id = $this->queryBuilder->table($this->tasksTable)->insert($newTask->getAttributes(['userName', 'email', 'description', 'status']));
        $newTask->id = $id;
        return $newTask;
    }

    /**
     * @param $editedTask \app\models\task\TaskEntity
     * @return \app\models\task\TaskEntity
     */
    public function editTask($editedTask)
    {
        $this->queryBuilder
            ->table($this->tasksTable)
            ->where('id', $editedTask->id)
            ->update($editedTask->getAttributes(['description']));
        return $editedTask;
    }

    /**
     * @param $taskId integer
     * @return null|TaskEntity
     */
    public function getTaskFromId($taskId)
    {
        $taskData = $this->queryBuilder
            ->table($this->tasksTable)
            ->select(['id', 'userName', 'email', 'description', 'status'])
            ->where('id', $taskId)
            ->get();
        if (!empty($taskData)) {
            $taskEntity = Factory::getInstance()->createTaskEntity();
            $taskEntity->load($taskData[0]);
            return $taskEntity;
        }
        return null;
    }

    /**
     * @param $task \app\models\task\TaskEntity
     * @return \app\models\task\TaskEntity
     */
    public function switchStatus($task)
    {
        $task->switchStatus();
        $this->queryBuilder
            ->table($this->tasksTable)
            ->where('id', $task->id)
            ->update($task->getAttributes(['status']));
        return $task;
    }
}
