<?php

declare(strict_types=1);

namespace App\Application\Mqtt\Controller;

use App\Annotation\Api;
use App\Application\Admin\Middleware\AdminMiddleware;
use App\Application\Mqtt\Service\MqttService;
use App\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\Session\Middleware\SessionMiddleware;

#[Middlewares([SessionMiddleware::class, AdminMiddleware::class])]
#[Controller(prefix: "/mqtt/mqtt")]
class MqttController extends AbstractController
{
    #[Inject]
    protected MqttService $mqttService;

    #[Api]
    #[GetMapping]
    public function index()
    {
        $client = $this->mqttService->getClient(md5("2"));
        $client->connect();
        //A1 03 21 01 05 08 04
        //A103410C0100000001010100005188000E5B
        $res = $client->publish("simps-mqtt/user/post/865743078832739",
            '{"data":"A103410C000000000101010000518800F398"}', MQTT_QOS_1);
        sleep(1);
        $res = $client->publish("simps-mqtt/user/get/865743078832739", '{"data":"A1032101050804"}', MQTT_QOS_1);
        $client->close();

        return $res;
    }
}
