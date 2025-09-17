<?php

namespace App\Application\Mqtt\Process;

use App\Application\Mqtt\Event\TopicRecvEvent;
use App\Application\Mqtt\Lib\RecvBuffer;
use App\Application\Mqtt\Service\MqttService;
use App\Exception\ErrorException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Process\AbstractProcess;
use Psr\EventDispatcher\EventDispatcherInterface;
use Simps\MQTT\Protocol\Types;
use function Hyperf\Support\env;

class MqttProcess extends AbstractProcess
{
    #[Inject]
    protected StdoutLoggerInterface $logger;

    #[Inject]
    protected EventDispatcherInterface $eventDispatcher;

    #[Inject]
    protected MqttService $mqttService;

    public function handle(): void
    {
        $client = $this->mqttService->getClient("MqttProcess-" . time());
        //TODO 根据业务需求订阅topics
        $topics = [
            "simps-mqtt/user/post/+" => MQTT_QOS_1,
            "simps-mqtt/user/get/+" => MQTT_QOS_1
        ];
        $c_res = $client->connect();
        $c_res_code = $c_res['code'] ?? -1;
        if ($c_res_code !== 0) {
            throw new ErrorException('连接错误');
        }
        // 订阅主题，只拿 $topics 的key拼接
        $topicKeys = array_keys($topics);
        $this->logger->info("MQTT " . env('MQTT_HOST') . " 连接成功 Subscribe: \n" . implode("\n", $topicKeys));
        $client->subscribe($topics);
        $timeSincePing = time();
        while (true) {
            try {
                $buffer = $client->recv();
                if ($buffer && $buffer !== true) {
                    $recv_buffer = new RecvBuffer($buffer);
//                    var_dump($recv_buffer->getType());
//                    var_dump($recv_buffer->getMessage());
//                    var_dump($recv_buffer->getQos());
//                    var_dump($recv_buffer->getTopic());
                    // QoS-1 PUBACK
                    if ($recv_buffer->getType() === Types::PUBLISH && $recv_buffer->getQos() === MQTT_QOS_1) {
                        $client->send([
                            'type' => Types::PUBACK,
                            'message_id' => $recv_buffer->getMessageId(),
                        ], false);
                    }
                    if ($buffer['type'] === Types::DISCONNECT) {
                        $this->logger->info("Mqtt Broker is disconnected");
                        $client->close();
                        break;
                    }
                    if ($recv_buffer->getTopic() !== '') {
                        $this->eventDispatcher->dispatch(new TopicRecvEvent($recv_buffer));
                    }
                }
                if ($timeSincePing <= (time() - $client->getConfig()
                            ->getKeepAlive())) {
                    $buffer = $client->ping();
                    if ($buffer) {
                        $timeSincePing = time();
                    }
                }
            } catch (\Throwable $e) {
                $this->logger->error($e->getMessage());
            }
        }
    }
}