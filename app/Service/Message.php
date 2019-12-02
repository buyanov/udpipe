<?php
namespace App\Service;

use ClickHouseDB\Client;
use React\EventLoop\LoopInterface;
use ClickHouseDB\Transport\StreamWrite;
use React\Eventloop\StreamSelectLoop;

class Message {

    /**
     * @var $loop StreamSelectLoop
     */
    protected static $loop;

    /**
     * @var $clickHouseClient Client;
     */
    protected static $clickHouseClient;

    protected static $json;

    public function __invoke(...$args)
    {
        if (\is_array($args) && \count($args) < 2) {
            return;
        }

        if (false !== ($startPos = strpos($args[0], '@@'))) {
            $jsonLog = substr($args[0], $startPos + 2);
            $json = json_decode($jsonLog, true);

            if (null === $json || json_last_error()) {
                return;
            }

            $timeLocal = strtotime($json['time_local']);
            $json['local_date'] = Date('Y-m-d', $timeLocal);
            $json['local_time'] = (string) $timeLocal;

            $json = (object) array_intersect_key($json, array_flip([
                'local_date','local_time','remote_addr','remote_user',
                'organiser_id','request_uri','status','body_bytes_sent',
                'request_time','upstream_addr','upstream_bytes_received',
                'upstream_cache_status','upstream_connect_time','upstream_header_time',
                'upstream_response_length','upstream_response_time'
            ]));

            $buffer = fopen('php://memory', 'rb+');
            fwrite($buffer, json_encode($json, JSON_FORCE_OBJECT) . PHP_EOL);
            rewind($buffer);
            $streamWrite = new StreamWrite($buffer);

            static::$clickHouseClient->streamWrite(
                $streamWrite,
                'INSERT INTO {table_name} FORMAT JSONEachRow',
                ['table_name'=>'access_log']
            );
        }

        static::$loop->futureTick(new static());
    }

    public static function withLoop(LoopInterface $loop, &$client)
    {
        static::$loop = $loop;
        static::$clickHouseClient = $client;
        return new static();
    }

}