<?php

namespace App\Application\Mqtt\Listener;

use App\Application\Mqtt\Event\TopicRecvEvent;
use App\Application\Mqtt\Service\MqttHandleService;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;

#[Listener]
class TopicRecvHandleListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            TopicRecvEvent::class,
        ];
    }

    public function process(object $event): void
    {
        if ($event instanceof TopicRecvEvent) {
            $recvBuffer = $event->getRecvBuffer();
            (new MqttHandleService())->handle($recvBuffer);
        }
    }
}