<?php

declare(strict_types=1);

namespace App\Application\Mqtt\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int            $id
 * @property string         $topic
 * @property string         $message
 * @property int            $type
 * @property int            $qos
 * @property int            $dup
 * @property int            $retain
 * @property int            $message_id
 * @property int            $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class MqttTopicRecv extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'mqtt_topic_recv';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'topic',
        'message',
        'type',
        'qos',
        'dup',
        'retain',
        'message_id',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'type' => 'integer',
        'qos' => 'integer',
        'dup' => 'integer',
        'retain' => 'integer',
        'message_id' => 'integer',
        'status' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    const STATUS_PENDING = 0;
    const STATUS_RECV = 1;
    const STATUS_HANDLE = 2;
}
