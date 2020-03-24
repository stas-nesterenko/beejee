<?php

namespace Bj\Models;

use BJ\AbstractModel;

class Users extends AbstractModel
{

    protected function getTableName()
    {
        return 'users';
    }

    protected function getFillable()
    {
        return ['login', 'password'];
    }
}
