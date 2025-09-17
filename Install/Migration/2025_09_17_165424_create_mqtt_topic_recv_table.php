<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mqtt_topic_recv', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('topic', 255);
            $table->string('message', 2048);
            $table->integer('type')
                ->default(0);
            $table->integer('qos')
                ->default(0);
            $table->integer('dup')
                ->default(0);
            $table->integer('retain')
                ->default(0);
            $table->integer('message_id')
                ->default(0);
            $table->tinyInteger('status')
                ->default(1)
                ->comment('状态 1 接收 2已处理');
            $table->datetimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mqtt_topic_recv');
    }
};
