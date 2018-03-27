<?php
namespace Sfynx\CacheBundle\Handler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

use Psr\Cache\CacheItemPoolInterface;
use Cache\SessionHandler\Psr6SessionHandler;
use Cache\Taggable\TaggablePSR6PoolAdapter;

use Sfynx\SpecificationBundle\Specification\Generalisation\InterfaceSpecification;
use Sfynx\CacheBundle\Handler\Specification\SpecIsEnabled;

use Sfynx\CacheBundle\Handler\Generalisation\Interfaces\FactoryPassInterface;
use Sfynx\CacheBundle\Handler\Generalisation\Interfaces\HandlerPassInterface;
use Sfynx\CacheBundle\Handler\Generalisation\AbstractHandlerPass;
use Sfynx\CacheBundle\Handler\Util\Pool\FixedTaggingCachePool;
use Sfynx\CacheBundle\Handler\Util\Bridge\SessionHandlerBridge;


use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcacheSessionHandler;

/**
 * Class SessionHandlerPass
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
class SessionHandlerPass extends AbstractHandlerPass
{
    /** @var string */
    const HANDLER_NAME = FactoryPassInterface::HANDLER_SESSION;

    /**
     * @return InterfaceSpecification
     */
    protected function doSpecification(): InterfaceSpecification
    {
        return new SpecIsEnabled();
    }

    /**
     * The process function check if request is valid
     * and do any post treatment (persisting the entity etc.)
     *
     * @return bool
     */
    protected function process(ContainerBuilder $container, array $config): bool
    {
        if (!empty($config['provider'])) {
            $adapter = new Reference($config['provider']);

            $container->register(sprintf('sfynx.cache.%s', static::HANDLER_NAME), SessionHandlerBridge::class)
                ->setFactory([SessionHandlerPass::class, 'getBridge'])
                ->addArgument(new Reference($config['provider']))
                ->addArgument($config)
                ->addArgument([static::HANDLER_NAME])
            ;
        }
        if (!empty($config['factory'])) {
            if (class_exists($config['factory'])) {
                $class = $config['factory'];
                $basename =(new \ReflectionClass($class))->getShortName();
                $id_service = sprintf('sfynx.cache.factory.%s', $basename);

                $container->register($id_service, $class);
            } else {
                $id_service = $config['factory'];
            }
            $factory = new Reference($id_service);
            $container->register(sprintf('sfynx.cache.%s', static::HANDLER_NAME), SessionHandlerBridge::class)
                ->setFactory([SessionHandlerPass::class, 'getBridgeFactory'])
                ->addArgument($factory)
                ->addArgument($config)
                ->addArgument([static::HANDLER_NAME]);
        }

        return true;
    }

    /**
     * @param CacheItemPoolInterface $pool
     * @param array                  $config
     *
     * @return Psr6SessionHandler
     */
    public static function getBridge(CacheItemPoolInterface $pool, array $config, array $tags): \SessionHandlerInterface
    {
        if ($config['use_tagging']) {
            $pool = new FixedTaggingCachePool(TaggablePSR6PoolAdapter::makeTaggable($pool), $tags);
        }

        array_map(function($key) use (&$config) {
            unset($config[$key]);
        }, ['enabled', 'provider','use_tagging', 'factory']);

        return new SessionHandlerBridge($pool, $config);
    }
}
