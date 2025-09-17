<?php

namespace App\Application\Mqtt\Event;

use App\Application\Mqtt\Lib\RecvBuffer;

class TopicRecvEvent
{
    public function __construct(protected RecvBuffer $recvBuffer) { }

    /**
     * @return RecvBuffer
     */
    public function getRecvBuffer(): RecvBuffer
    {
        return $this->recvBuffer;
    }
}