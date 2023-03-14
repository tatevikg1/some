<?php

namespace App\Console\Commands\Overrides;


class ModelMakeCommand extends \Illuminate\Foundation\Console\ModelMakeCommand
{
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\\Models';
    }
}
