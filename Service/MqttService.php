<?php

declare(strict_types=1);

namespace App\Application\Mqtt\Service;

use Simps\MQTT\Client;
use Simps\MQTT\Config\ClientConfig;
use function Hyperf\Support\env;

class MqttService
{
    protected string $username = '';
    protected string $password = '';
    protected string $host = '';
    protected int $port = 1883;

    public function __construct()
    {
        $this->username = env('MQTT_USERNAME', '');
        $this->password = env('MQTT_PASSWORD', '');
        $this->host = env('MQTT_HOST', '');
        $this->port = intval(env('MQTT_PORT', 1883));
    }

    public function getClient(string $clientId, int $keepAlive = 10): Client
    {
        $configObj = new ClientConfig();
        $configObj->setUserName($this->username)
            ->setPassword($this->password)
            ->setClientId($clientId)
            ->setMaxAttempts($keepAlive)
            ->setSwooleConfig([
                'open_mqtt_protocol' => true,
                'package_max_length' => 2 * 1024 * 1024,
                'connect_timeout' => 5.0,
                'write_timeout' => 5.0,
                'read_timeout' => 5.0,
            ]);

        return new Client($this->host, $this->port, $configObj);
    }
}
