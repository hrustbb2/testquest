<?php

namespace app\models\task;

use app\lib\BaseEntity;

class TaskEntity extends BaseEntity
{
    const STATUS_NEW = 10;

    const STATUS_DONE = 20;

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $userName;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $description;

    /**
     * @var integer
     */
    public $status = self::STATUS_NEW;

    /**
     * @return string
     */
    public function displayStatus()
    {
        if ($this->status == self::STATUS_NEW) {
            return 'NEW';
        }
        if ($this->status == self::STATUS_DONE) {
            return 'DONE';
        }
    }

    /**
     * @return string
     */
    public function displayUserName()
    {
        return htmlspecialchars($this->userName);
    }

    /**
     * @return string
     */
    public function displayEmail()
    {
        return htmlspecialchars($this->email);
    }

    /**
     * @return string
     */
    public function displayDescription()
    {
        return htmlspecialchars($this->description);
    }

    /**
     * @return void
     */
    public function switchStatus()
    {
        if ($this->status == self::STATUS_DONE) {
            $this->status = self::STATUS_NEW;
        } else {
            $this->status = self::STATUS_DONE;
        }
    }
}
