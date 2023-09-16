<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    private AMQPSSLConnection $connection;
    private const TYPE_DIRECT_EXCHANGE = 'direct';

    public function publish(string $jobKey, array $message): void
    {
        $channel = $this->getConnectionAndChannel();
        $this->setUpQueue($channel, $jobKey);
        $msg = new AMQPMessage(json_encode($message));
        $channel->basic_publish($msg, $jobKey, $jobKey);
        $this->close($channel);
    }

    public function consume(string $jobKey): void
    {
        $channel = $this->getConnectionAndChannel();
        $class = config('rabbitmq.class_mapping.' . $jobKey);
        $channel->queue_declare(
            $jobKey,
            false,
            true,
            false,
            false,
        );
        $channel->basic_consume(
            $jobKey,
            '',
            false,
            true,
            false,
            false,
            (new $class)
        );
        while ($channel->is_consuming()) {
            $channel->wait();
        }
        $this->close($channel);
    }

    /**
     * @return AbstractChannel|AMQPChannel
     * @throws Exception
     */
    private function getConnectionAndChannel(): AbstractChannel|AMQPChannel
    {
        $this->connection = new AMQPSSLConnection(
            config('rabbitmq.connections.default.host'),
            config('rabbitmq.connections.default.port'),
            config('rabbitmq.connections.default.login'),
            config('rabbitmq.connections.default.password'),
            config('rabbitmq.connections.default.vhost'),
//            config('rabbitmq.sslOptions')
        );

        return $this->connection->channel();
    }

    /**
     * @param mixed $channel
     * @return void
     * @throws Exception
     */
    private function close(mixed $channel): void
    {
        $channel->close();
        $this->connection->close();
    }

    private function setUpQueue(AMQPChannel $channel, $jobKey): void
    {
        $channel->exchange_declare($jobKey, self::TYPE_DIRECT_EXCHANGE, false, false, false);
        $channel->queue_declare($jobKey, false, true, false, false);
        $channel->queue_bind($jobKey, $jobKey, $jobKey);
    }
}
