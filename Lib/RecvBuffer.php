<?php

namespace App\Application\Mqtt\Lib;

use App\Application\Mqtt\Model\MqttTopicRecv;
use http\Message;

class RecvBuffer
{
    protected int $qos = 0;
    protected int $retain = 0;
    protected string $topic = '';
    protected string $message = '';
    protected int $message_id = 0;

    protected int $type = 0;
    protected int $dup = 0;

    protected ?MqttTopicRecv $topicRecvModel = null;


    public function __construct(protected array $buffer)
    {
        $this->type = $buffer['type'] ?? 0;
        $this->qos = $buffer['qos'] ?? 0;
        $this->dup = $buffer['dup'] ?? 0;
        $this->retain = $buffer['retain'] ?? 0;
        $this->topic = $buffer['topic'] ?? '';
        $this->message = $buffer['message'] ?? '';
        $this->message_id = $buffer['message_id'] ?? 0;
    }

    public function getQos(): int
    {
        return $this->qos;
    }

    public function getRetain(): int
    {
        return $this->retain;
    }

    public function getTopic(): string
    {
        return $this->topic;
    }

    public function getMessage(): string
    {
        if (!ctype_print($this->message)) {
            $this->message = strtoupper(bin2hex($this->message));
        }

        return $this->message;
    }

    public function getMessageId(): int
    {
        return $this->message_id;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getDup(): int
    {
        return $this->dup;
    }

    public function saveRecvBuffer(): MqttTopicRecv
    {
        $this->topicRecvModel = MqttTopicRecv::create($this->buffer + ['status' => MqttTopicRecv::STATUS_RECV]);

        return $this->topicRecvModel;
    }

    public function getTopicRecvModel(): MqttTopicRecv
    {
        if (!$this->topicRecvModel) {
            $this->topicRecvModel = $this->saveRecvBuffer();
        }

        return $this->topicRecvModel;
    }
}