<?php

namespace App\Application\Mqtt\Service\Handle;

use App\Application\Mqtt\Lib\RecvBuffer;

class TopicUserPostHandle extends AbstractTopicHandle
{
    public function handle(RecvBuffer $buffer): bool
    {
        //正则表达式 从  simps-mqtt/user/post/{device_id}  拿到 device_id
        $is_match = preg_match('/simps-mqtt\/user\/post\/(\d+)/', $buffer->getTopic(), $matches);
        if ($is_match) {
            $device_id = $matches[1] ?? '';

            //TODO 对 $message 进行解析
            $message = $buffer->getMessage();
            var_dump($device_id, $message);
        }

        return true;
    }
}