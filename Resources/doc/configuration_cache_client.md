# SfynxCacheBundle documentation of Sfynx cache client

- [dumpercache client service](#dumpercache-client-service)
- [filecache client service](#filecache-client-service)
- [rediscache client service](#rediscache-client-service)

## dumpercache client service
This bundle even allows you to store your data in one of your cache files.


Enable your Sfynx session cache handler:

``` yaml
sfynx_cache:
    dumpercache:
        enabled: true
```


To use this with Symfony, just do it like following example.

``` php
$options     = [
  'namespace_dir'  => 'Sfynx/Page',
  'namespace_file' => 'appCache',
  'dumper_class'   => 'Sfynx\\CmfBundle\\Dumper\\PhpPageDumper',
  'dumper_options' => [],
  'cache_metadata' => null
];
$this->container->get("sfynx.cache.dumpercache")->setOptions($options)->set($entity->getId(), $entity, $ttl = 3600);
```

## filecache client service
This bundle even allows you to store your data in one of your cache files.


Enable your Sfynx session cache handler:

``` yaml
sfynx_cache:
    filecache:
        enabled: true
```

To use this with Symfony, just do it like following example.

``` php
$this->get("sfynx.cache.filecache")->getClient()->setPath($dossier);
$url_public_media = $this->get("sfynx.cache.filecache")->get($format.$pattern.$id.'_'.$timestamp);

if (!$url_public_media) {
    $url_public_media = $media->getUrl($media->getExtension(), $params);
    $this->container->get("sfynx.cache.filecache")->set($format.$pattern.$id.'_'.$timestamp, $url_public_media, $ttl = 3600);
}
```

## rediscache client service
This bundle even allows you to store your redis data in one of your cache clusters.


First, create yout cache provider with Adapter Bundle

``` yaml
cache_adapter:
    providers:
        ...
        rediscache:
          factory: 'cache.factory.predis'
          options:
            dsn: '%redis_connection%/7'
```

Secondly, enable your Sfynx rediscache client:

``` yaml
sfynx_cache:
    client_rediscache:
        enabled: true
        provider: 'cache.provider.rediscache'
```

To use this with Symfony, just do it like following example.

``` php
$url_public_media = $this->get("sfynx.cache.rediscache")->get($format.$pattern.$id.'_'.$timestamp);

if (!$url_public_media) {
    $url_public_media = $media->getUrl($media->getExtension(), $params);
    $this->container->get("sfynx.cache.rediscache")->set($format.$pattern.$id.'_'.$timestamp, $url_public_media, $ttl = 3600);
}
```
