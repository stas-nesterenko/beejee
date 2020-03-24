<?php


namespace BJ\Models;


use BJ\AbstractModel;

class Task extends AbstractModel
{

    /**
     * @inheritDoc
     */
    protected function getTableName()
    {
        return 'tasks';
    }

    /**
     * @inheritDoc
     */
    protected function getFillable()
    {
        return ['name', 'email', 'body', 'active', 'edited'];
    }
}
