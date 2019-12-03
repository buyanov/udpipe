#UDPIPE

Запуск сервера осуществляется командой
```bash
./bin/udpipe server:start --address=127.0.0.1 --port=2514
```

в продакшене нужно использовать supervisor или PM2 например

настройка подключения к ClickHouse осуществляется через .env или переменные окружения

для проверки можно использовать контейнер

```bash
docker run -d --name clickhouse -p8123:8123 -p9000:9000 --ulimit nofile=262144:262144 yandex/clickhouse-server
```

а чтобы отправить данные и проверить как их обработает сервер без настройки nginx используем netcat

```bash
nc -4Auv 127.0.0.1 2514
```

пример лога есть в файле ./var/log/access.log

```bash
echo '@@{"time_local":"01/Dec/2019:06:43:11 +0000","remote_addr":"172.17.0.1","remote_user":"","request_uri": "/","status": "200","body_bytes_sent":"18","request_time":"0.000","http_referrer":"","upstream_addr":"","upstream_bytes_received":"","upstream_cache_status":"","upstream_connect_time":"","upstream_header_time":"","upstream_response_length":"","upstream_response_time":""}' | nc -4uv 127.0.0.1 2514
```

можно просто запустить все сервисы разом docker-compose
