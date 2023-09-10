<?php

namespace App\Jobs\RabbitMQ;

interface RabbitMQJobInterface
{
    public function work(array $data):void;
}
