# Configuration Reference of Adapter Bundle

All available configuration options are listed below with their default values.

``` yaml
#
# AdapterBundle configuration
#       
cache_adapter:
  providers:
    serializer_redis:
      factory: 'cache.factory.predis'
      options:
        dsn: '%redis_connection%/1'
    validation_redis:
      factory: 'cache.factory.predis'
      options:
        dsn: '%redis_connection%/2'
    annotation_redis:
      factory: 'cache.factory.predis'
      options:
        dsn: '%redis_connection%/3'
    session_redis:
      factory: 'cache.factory.predis'
      options:
        dsn: '%redis_connection%/4'
    doctrine_metadata_redis:
      factory: 'cache.factory.predis'
      options:
        dsn: '%redis_connection%/5'
    doctrine_result_redis:
      factory: 'cache.factory.predis'
      options:
        dsn: '%redis_connection%/6'
#        prefix: foo
#        profile: 2.4
#        connection_timeout: 10
#        connection_persistent: true
#        read_write_timeout: 30
#        iterable_multibulk: false
#        throw_errors: true
#        cluster: Snc\RedisBundle\Client\Predis\Connection\PredisCluster
#        replication: false
    rediscache:
      factory: 'cache.factory.predis'
      options:
        dsn: '%redis_connection%/7'
```
