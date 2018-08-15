# SfynxCacheBundle documentation

This bundle is responsible to :
* integrate your PSR-6 compliant cache service with the framework
* cache your sessions, Doctrine queries and results and metadata, validation, serialization and annotation
* manager everything in cache with ttl and key from interface cache client

The following documents are available:

- [Configuration reference of Adapter Bundle ](configuration_reference_adapter)
- [Configuration reference of SfynxCacheBundle ](configuration_reference)
- [SfynxCacheBundle documentation of Symfony Cache components](configuration_cache_symfony)
- [SfynxCacheBundle documentation of interface cache client](configuration_cache_client)
- [ChangeLog](#changelog)
- [Todo](#todo)

## ChangeLog

| Date | Version | Auteur | Description |
| ------ | ----------- | ---- | ----------- |
| 20/07/2018   | 1.0.0 | EDL | documentation initialization|

## Todo

- Add route cache handler
- Create memecache and filecache client from CompilerPass