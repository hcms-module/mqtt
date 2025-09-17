<?php

declare(strict_types=1);

namespace App\Application\Mqtt\Service;

use App\Application\Mqtt\Lib\RecvBuffer;
use App\Application\Mqtt\Model\MqttTopicRecv;
use App\Application\Mqtt\Service\Handle\AbstractTopicHandle;
use App\Application\Mqtt\Service\Handle\TopicUserGetHandle;
use App\Application\Mqtt\Service\Handle\TopicUserPostHandle;
use Hyperf\Stringable\Str;

class MqttHandleService
{
    protected array $topics = [
        "simps-mqtt/user/get" => TopicUserGetHandle::class,
        "simps-mqtt/user/post" => TopicUserPostHandle::class,
    ];

    public function handle(RecvBuffer $buffer)
    {
        $buffer->saveRecvBuffer();
        foreach ($this->topics as $topic => $handle) {
            if (Str::startsWith($buffer->getTopic(), $topic)) {
                $handle_object = new $handle();
                if ($handle_object instanceof AbstractTopicHandle) {
                    if ($handle_object->handle($buffer)) {
                        $buffer->getTopicRecvModel()->status = MqttTopicRecv::STATUS_HANDLE;
                        $buffer->getTopicRecvModel()
                            ->save();
                    }
                }
            }
        }
    }
}
