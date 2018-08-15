#Configuration Reference

All available configuration options are listed below with their default values.

``` yaml
#
# SfynxCacheBundle configuration
#       
sfynx_cache:
  doctrine:
    enabled: true
    metadata:
      use_tagging: true
      provider: 'cache.provider.doctrine_metadata_redis'
      entity_managers: [ default ]
    result:
      use_tagging: true
      provider: 'cache.provider.doctrine_result_redis'
      entity_managers: [ default ]
    query:
      use_tagging: true
      entity_managers:   [ default ]       # the name of your entity_manager connection
#      document_managers: [ default ]       # the name of your document_manager connection
      factory: 'Sfynx\CacheBundle\Adapter\PredisClusterFactory' # 'sfynx.cache.factory.predis_cluster'
      factory_options:
         parameters:
            - '%redis_connection%/11'
            - '%redis_connection%/12'
            - '%redis_connection%/13'
         options:
           replication: sentinel
  session:
    enabled: true
    provider: 'cache.provider.session_redis'
    use_tagging: true
    ttl: 7200
  annotation:
    enabled: true
    provider: 'cache.provider.annotation_redis'
    use_tagging: true
  validation:
    enabled: true
    provider: 'cache.provider.validation_redis'
    use_tagging: true
  serializer:
    enabled: true
    provider: 'cache.provider.serializer_redis'
    use_tagging: true
  client_rediscache:
    enabled: true
    provider: 'cache.provider.rediscache'
  client_filecache:
    enabled: true
  client_dumpercache:
    enabled: true
```
