# SfynxCacheBundle documentation of Symfony Cache components

- [Session configuration](#session-configuration)
- [Doctrine configuration](#doctrine-configuration)
- [Validation configuration](#validation-configuration)
- [Serialization configuration](#serialization-configuration)
- [Annotation configuration](#annotation-configuration)

## Session configuration
This bundle even allows you to store your session data in one of your cache clusters.


First, create yout cache provider with Adapter Bundle

``` yaml
cache_adapter:
    providers:
        ...
        session_redis:
          factory: 'cache.factory.predis'
          options:
            dsn: '%redis_connection%/4'
```

Secondly, enable your Sfynx session cache handler:

``` yaml
sfynx_cache:
    session:
        enabled: true
        provider: 'cache.provider.session_redis'
        use_tagging: true
        ttl: 7200
```

Third, enabled in app/config/config.yml your Symfony session service handler
``` yaml
framework:
    ...
    session:
        ...
        handler_id: 'sfynx.cache.session'
```

## Doctrine configuration
This bundle even allows you to store your doctrine metadata, result and query data in one of your cache clusters.


First, create yout cache provider with Adapter Bundle

``` yaml
cache_adapter:
    providers:
        ...
        doctrine_metadata_redis:
          factory: 'cache.factory.predis'
          options:
            dsn: '%redis_connection%/5'
        doctrine_result_redis:
          factory: 'cache.factory.predis'
          options:
            dsn: '%redis_connection%/6'
```

Secondly, enable your Sfynx doctrine cache handler:

``` yaml
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
```

Third, enabled in app/config/config.yml your Symfony Doctrine service cache
``` yaml
doctrine:
    ...
    orm:
        ...
        entity_managers:
            default:
                ...
                metadata_cache_driver:
                    type: 'service'
                    id: 'sfynx.cache.doctrine.metadata'
                query_cache_driver:
                    type: 'service'
                    id: 'sfynx.cache.doctrine.query'
                result_cache_driver:
                    type: 'service'
                    id: 'sfynx.cache.doctrine.result'
```

To use this with Doctrine's entity manager, just make sure you have useResultCache and/or  useQueryCache set to true.

``` php
$em = $this->get('doctrine.orm.entity_manager');
$q = $em->('SELECT u.* FROM Acme\User u');
$q->useResultCache(true, 3600);
$result = $q->getResult();
```

## Validation configuration
This bundle even allows you to store your Validation data in one of your cache clusters.


First, create yout cache provider with Adapter Bundle

``` yaml
cache_adapter:
    providers:
        ...
        validation_redis:
          factory: 'cache.factory.predis'
          options:
            dsn: '%redis_connection%/2'
```

Secondly, enable your Sfynx Validation cache handler:

``` yaml
sfynx_cache:
    validation:
        enabled: true
        provider: 'cache.provider.validation_redis'
        use_tagging: true
```

Third, enabled in app/config/config.yml your Symfony Validation service cache
``` yaml
framework:
    ...
    validation:
        enabled: true
        enable_annotations: true
        cache: 'sfynx.cache.validation'
```

## Serialization configuration
This bundle even allows you to store your Serialization data in one of your cache clusters.


First, create yout cache provider with Adapter Bundle

``` yaml
cache_adapter:
    providers:
        ...
        serializer_redis:
          factory: 'cache.factory.predis'
          options:
            dsn: '%redis_connection%/1'
```

Secondly, enable your Sfynx Serialization cache handler:

``` yaml
sfynx_cache:
    session:
        enabled: true
        provider: 'cache.provider.serializer_redis'
        use_tagging: true
```

Third, enabled in app/config/config.yml your Symfony Serialization service cache
``` yaml
framework:
    ...
    serializer:
        cache: 'sfynx.cache.serializer'
```
## Annotation configuration
This bundle even allows you to store your annotation data in one of your cache clusters.


First, create yout cache provider with Adapter Bundle

``` yaml
cache_adapter:
    providers:
        ...
        annotation_redis:
          factory: 'cache.factory.predis'
          options:
            dsn: '%redis_connection%/3'
```

Secondly, enable your Sfynx annotation cache handler:

``` yaml
sfynx_cache:
    session:
        enabled: true
        provider: 'cache.provider.annotation_redis'
        use_tagging: true
```

Third, enabled in app/config/config.yml your Symfony annotation service cache
``` yaml
framework:
    ...
    annotations:
        cache: 'sfynx.cache.annotation'
```