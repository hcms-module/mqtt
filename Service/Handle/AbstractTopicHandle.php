<?php

namespace App\Application\Mqtt\Service\Handle;

use App\Application\Mqtt\Lib\RecvBuffer;

abstract class AbstractTopicHandle
{
    abstract public function handle(RecvBuffer $buffer): bool;
}