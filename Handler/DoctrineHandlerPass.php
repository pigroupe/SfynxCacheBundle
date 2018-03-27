<?php
namespace Sfynx\CacheBundle\Handler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

use Psr\Cache\CacheItemPoolInterface;
use Cache\Bridge\Doctrine\DoctrineCacheBridge;
use Cache\Prefixed\PrefixedCachePool;
use Cache\Taggable\TaggablePSR6PoolAdapter;

use Sfynx\SpecificationBundle\Specification\Generalisation\InterfaceSpecification;
use Sfynx\CacheBundle\Handler\Specification\SpecIsDoctrineBridgeExists;
use Sfynx\CacheBundle\Handler\Specification\SpecIsEnabled;
use Sfynx\CacheBundle\Handler\Specification\SpecIsOrmManagersIsDefined;
use Sfynx\CacheBundle\Handler\Specification\SpecIsOdmManagersIsDefined;

use Sfynx\CacheBundle\Adapter\DoctrinePredisFactory;
use Sfynx\CacheBundle\Handler\Generalisation\Interfaces\FactoryPassInterface;
use Sfynx\CacheBundle\Handler\Generalisation\Interfaces\HandlerPassInterface;
use Sfynx\CacheBundle\Handler\Generalisation\AbstractHandlerPass;
use Sfynx\CacheBundle\Handler\Util\Pool\FixedTaggingCachePool;

/**
 * Class DoctrineHandlerPass
 *
 * @category   Bundle
 * @package    Sfynx\CacheBundle
 * @subpackage Handler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @copyright  2015 PI-GROUPE
 * @license    http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version    2.3
 * @link       http://opensource.org/licenses/gpl-license.php
 * @since      2015-02-16
 */
class DoctrineHandlerPass extends AbstractHandlerPass
{
    /** @var string */
    const HANDLER_NAME = FactoryPassInterface::HANDLER_DOCTRINE;

    /**
     * @return InterfaceSpecification
     */
    protected function doSpecification(): InterfaceSpecification
    {
        return (new SpecIsDoctrineBridgeExists())
            ->AndSpec(new SpecIsEnabled());
    }

    /**
     * The process function check if request is valid
     * and do any post treatment (persisting the entity etc.)
     *
     * @return bool
     */
    protected function process(ContainerBuilder $container, array $config): bool
    {
        foreach (['metadata', 'result', 'query'] as $cacheType) {
            $typeConfig = $config[$cacheType];
            $bridgeServiceId = sprintf('sfynx.cache.%s.%s', static::HANDLER_NAME, $cacheType);

            if (!empty($typeConfig['provider'])) {
                $adapter = new Reference($typeConfig['provider']);
                $container->register($bridgeServiceId, DoctrineCacheBridge::class)
                    ->setFactory([DoctrineHandlerPass::class, 'getBridge'])
                    ->addArgument($adapter)
                    ->addArgument($typeConfig)
                    ->addArgument([static::HANDLER_NAME, $cacheType]);
            }
            if (!empty($typeConfig['factory'])) {
                if (class_exists($typeConfig['factory'])) {
                    $class = $typeConfig['factory'];
                    $basename =(new \ReflectionClass($class))->getShortName();
                    $id_service = sprintf('sfynx.cache.factory.%s', $basename);

                    $container->register($id_service, $class);
                } else {
                    $id_service = $typeConfig['factory'];
                }
                $factory = new Reference($id_service);
                $container->register($bridgeServiceId, DoctrineCacheBridge::class)
                    ->setFactory([DoctrineHandlerPass::class, 'getBridgeFactory'])
                    ->addArgument($factory)
                    ->addArgument($typeConfig)
                    ->addArgument([static::HANDLER_NAME, $cacheType]);
            }

            $spec = new SpecIsOrmManagersIsDefined($cacheType);
            if ($spec->isSatisfiedBy($this->object)) {
                $this->registerAliasManager($container, $bridgeServiceId, $cacheType, 'orm', $typeConfig['entity_managers']);
            }
            $spec = new SpecIsOdmManagersIsDefined($cacheType);
            if ($spec->isSatisfiedBy($this->object)) {
                dump($typeConfig);exit;
                $this->registerAliasManager($container, $bridgeServiceId, $cacheType, 'odm', $typeConfig['document_managers']);
            }
        }

        return true;
    }

    /**
     * @param CacheItemPoolInterface $pool
     * @param array $config
     * @param array $tags
     *
     * @return DoctrineCacheBridge
     */
    public static function getBridge(CacheItemPoolInterface $pool, array $config, array $tags)
    {
        if ($config['use_tagging']) {
            $pool = new FixedTaggingCachePool(TaggablePSR6PoolAdapter::makeTaggable($pool), $tags);
        }
        if (!empty($config['prefix'])) {
            $pool = new PrefixedCachePool($pool, $config['prefix']);
        }
        return new DoctrineCacheBridge($pool);
    }
}
