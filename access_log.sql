CREATE TABLE default.access_log
(
    local_date Date,
    local_time DateTime,
    remote_addr String,
    remote_user String,
    organiser_id UInt64,
    request_uri String,
    status UInt16,
    body_bytes_sent UInt64,
    request_time Float32,
    upstream_addr String,
    upstream_bytes_received UInt64,
    upstream_cache_status String,
    upstream_connect_time Float32,
    upstream_header_time Float32,
    upstream_response_length UInt64,
    upstream_response_time Float32
)
ENGINE = MergeTree()
PARTITION BY toYYYYMMDD(local_date)
ORDER BY (local_date, organiser_id)
SETTINGS index_granularity = 8192