<?php

namespace App\Policies;

use App\Models\BaseModel;
use ReflectionClass;

class BasePolicy
{
    protected function getModel(array|BaseModel $args): BaseModel
    {
        if ($args instanceof BaseModel) {
            return $args;
        }
        $policyClassName = (new ReflectionClass($this))->getShortName();
        $modelClassName = 'App\\Models\\' . (str_replace("Policy", "", $policyClassName));

        /** @var BaseModel $modelClassName */
        return $modelClassName::withTrashed()->find($args['id']);
    }
}
